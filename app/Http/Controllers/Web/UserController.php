<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;
    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request): View
    {
        $request->validate([
            'per_page' => 'integer|max:30',
            'page' => 'integer',
        ]);

        $users = $this->userService->getAll($request->per_page ?: 10);
        return view('users.index', compact("users"));
    }

    public function show(User $user): View
    {
        $user = $this->userService->getOne($user);
        return view('users.show', compact("user"));
    }

    function create($user): View
    {
        $user = $this->userService->getOne($user);
        return view('users.create', compact("user"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $user = $this->userService->create($request->only('name', 'email'), $request->file('avatar'));
        return redirect('users');
    }


    function edit(User $user): View
    {
        return view('users.edit', compact("user"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, User $user)
    {
        $this->userService->update($user, $request->only('name', 'email'), $request->file('avatar'));
        $this->userService->syncAddresses($user, $request->validated('addresses', []));

        return redirect(route('users.show', ['user' => $user]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->delete($user);
        return redirect('users');
    }
}
