<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserService
{
    function getAll(int $perPage): LengthAwarePaginator;
    function getAllDeleted(int $perPage): LengthAwarePaginator;
    function getOne(User|int $user): User;
    function create(array $userData, ?UploadedFile $avatar): User;
    function update(User $user, array $userData, ?UploadedFile $avatar): User;
    function delete(User $user): User;
    function permanentlyDelete(int $user): User;
    function restore(int $user): User;
    function syncAddresses(User $user, array $addresses): Collection;
}
