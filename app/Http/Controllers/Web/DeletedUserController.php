<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DeletedUserController extends Controller
{
    private $userService;
    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    function index(Request $request): View
    {
        $request->validate([
            'per_page' => 'integer|max:30',
            'page' => 'integer',
        ]);

        $deletedUsers = $this->userService->getAllDeleted($request->per_page ?: 10);

        return view('deleted-users.index', ['users' => $deletedUsers]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $user)
    {
        $this->userService->permanentlyDelete($user);
        return redirect('deleted-users');
    }

     /**
     * Restore the specified resource from storage.
     */
    public function restore(int $user)
    {
        $this->userService->restore($user);
        return redirect('deleted-users');
    }
}
