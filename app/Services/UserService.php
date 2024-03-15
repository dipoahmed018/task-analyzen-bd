<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\UserService as ContractsUserService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService implements ContractsUserService
{
    function getAll(): Paginator
    {
        return User::whereNot('id', Auth::id())->simplePaginate();
    }

    function getDeleted(): Paginator
    {
        return User::onlyTrashed()->simplePaginate();
    }

    function create(array $userData, UploadedFile $avatar): User
    {
        try {
            DB::beginTransaction();

            $userData['avatar'] = $avatar->store('avatar', ['disk' => 'public']);
            $user = User::create($userData);

            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            info($userData);
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

    function permanentlyDelete(User $user): User
    {
        $user->forceDelete();
        return $user;
    }

    private function updateAvatar(User $user, UploadedFile $file)
    {
        $avatarPath = $file->store('avatar', ['disk' => 'public']);
        $user->avatar && Storage::delete($user->avatar);

        return $avatarPath;
    }
}
