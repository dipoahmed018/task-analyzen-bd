<?php

namespace App\Services;

use App\Mail\UserCreationComplete;
use App\Models\User;
use App\Models\UserAddress;
use App\Services\Contracts\UserService as ContractsUserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService implements ContractsUserService
{
    function getAll($perPage = 10): LengthAwarePaginator
    {
        return User::whereNot('id', Auth::id())->orderBy('name')->paginate($perPage)->withQueryString();
    }


    function getOne($user): User
    {
        $user = $user instanceof User ? $user : User::findOrFail($user);
        $user->loadMissing(['addresses']);
        $user->setAppends(['avatar_url']);

        return $user;
    }

    function getAllDeleted($perPage = 10): LengthAwarePaginator
    {
        return User::onlyTrashed()->orderBy('deleted_at')->paginate($perPage)->withQueryString();
    }

    function create(array $userData, UploadedFile $avatar = null): User
    {
        try {
            DB::beginTransaction();

            $userData['avatar'] = $avatar?->store('avatar', ['disk' => 'public']);
            $password = Str::random(8);
            $userData['password'] = Hash::make($password);
            $user = User::create($userData);

            DB::commit();

            Mail::to($user->email)->send(new UserCreationComplete($user, $password));
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            isset($userData['avatar']) && Storage::disk('public')->delete($userData['avatar']);
            throw $th;
        }
    }

    function update(User $user, array $userData, UploadedFile $avatar = null): User
    {
        try {
            DB::beginTransaction();

            if ($avatar) {
                $userData['avatar'] = $this->updateAvatar($user, $avatar);
            }
            $user->update($userData);
            DB::commit();

            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            isset($userData['avatar']) && Storage::disk('public')->delete($userData['avatar']);
            throw $th;
        }
    }

    function delete(User $user): User
    {
        $user->delete();
        return $user;
    }

    function permanentlyDelete(int $user): User
    {
        $user = User::withTrashed()->findOrFail($user);
        $user->forceDelete();
        return $user;
    }

    function restore(int $user): User
    {
        $user = User::withTrashed()->findOrFail($user);
        $user->restore();
        return $user;
    }

    function syncAddresses(User $user, array $addresses): Collection
    {
        $addresses = collect($addresses);

        // Delete Addresses
        $deletAddresses =  $user->addresses()->get()->whereNotIn('id', $addresses->pluck('id'))->pluck('id');
        $user->addresses()->whereIn('id', $deletAddresses)->delete();

        // Update addresses
        $updateAddresses = $addresses->whereNotIn('id', $deletAddresses)->whereNotNull('id')->map(fn ($addr) => [...$addr, 'user_id' => $user->id])->toArray();
        UserAddress::upsert($updateAddresses, ['id'], ['address']);

        // Create new address
        $createAddresses = $addresses->whereNull('id');
        $user->addresses()->saveMany($createAddresses->map(fn ($addr) => new UserAddress($addr)));
        return $user->addresses()->get();
    }

    private function updateAvatar(User $user, UploadedFile $file)
    {
        $avatarPath = $file->store('avatar', ['disk' => 'public']);
        $user->avatar && Storage::disk('public')->delete($user->avatar);

        return $avatarPath;
    }
}
