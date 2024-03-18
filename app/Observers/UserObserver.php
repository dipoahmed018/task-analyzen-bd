<?php

namespace App\Observers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $userService = app(UserService::class);
        if (request()->has('addresses')) {
            $userService->syncAddresses($user, array_filter(request('addresses'), fn ($addr) => $addr['address']));
        }
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $user->avatar && Storage::disk('public')->delete($user->avatar);
    }
}
