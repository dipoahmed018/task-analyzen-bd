<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;

interface UserService
{
    function getAll(): Paginator;
    function getDeleted(): Paginator;
    function create(array $userData, UploadedFile $avatar): User;
    function update(User $user, array $userData, UploadedFile $avatar = null): User;
    function delete(User $user): User;
    function permanentlyDelete(User $user): User;
}
