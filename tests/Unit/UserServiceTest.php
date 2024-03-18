<?php

use App\Models\User;
use App\Models\UserAddress;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\assertNull;

test('can get paginated list of users excluding the current user', function () {
    $user = User::factory()->create();
    Auth::shouldReceive('id')->andReturn($user->id);
    User::factory()->count(2)->create();

    $service = app(UserService::class);
    $response = $service->getAll();

    assertNull($response->where('id', $user->id)->first());
    expect($response->count())->toBe(2);
    expect($response->items())->each->toBeInstanceOf(User::class);
});

test('can get a single user with addresses and avatar url', function () {
    $user = User::factory()->create();
    $user->addresses()->save(UserAddress::factory()->make());

    $service = app(UserService::class);
    $loadedUser = $service->getOne($user);

    expect($loadedUser->addresses)->toHaveCount(1);
    expect($loadedUser->avatar_url)->not->toBeNull();
});

test('can get paginated list of deleted users', function () {
    $user = User::factory()->create();
    $user->delete();

    $service = app(UserService::class);
    $response = $service->getAllDeleted();

    expect($response->count())->toBe(1);
    expect($response->items())->each->toBeInstanceOf(User::class);
});

test('can create a new user', function () {
    $userData = User::factory()->make()->toArray();
    $file = UploadedFile::fake()->image('avatar.png');

    $service = app(UserService::class);
    $user = $service->create($userData, $file);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->avatar)->toBe("avatar/" . $file->hashName());
    Storage::disk('public')->assertExists("avatar/" . $file->hashName());

    $invalidData = ['name' => 'Will Fail'];
    expect(fn() => $service->create($invalidData, $file))->toThrow(QueryException::class);
    Storage::disk('public')->assertMissing("avatar/" . $file->hashName());

});

test('can update user details and avatar', function () {
    $user = User::factory()->create();
    $previousAvatarPath = $user->avatar;
    $userData = ['name' => 'Updated Name'];
    $file = UploadedFile::fake()->image('avatar.png');

    $service = app(UserService::class);
    $updatedUser = $service->update($user, $userData, $file);

    Storage::disk('public')->assertMissing($previousAvatarPath);
    expect($updatedUser->name)->toBe('Updated Name');
    expect($updatedUser->avatar)->toBe("avatar/" . $file->hashName());

});

test('can soft delete a user', function () {
    $user = User::factory()->create();

    $service = app(UserService::class);
    $deletedUser = $service->delete($user);

    expect($deletedUser)->toBeInstanceOf(User::class);
    expect($deletedUser->trashed())->toBeTrue();
});

test('can permanently delete a user', function () {
    $user = User::factory()->create();
    $user->delete();

    $service = app(UserService::class);
    $permanentlyDeletedUser = $service->permanentlyDelete($user->id);

    expect($permanentlyDeletedUser)->toBeInstanceOf(User::class);
    expect(User::withTrashed()->find($user->id))->toBeNull();
    Storage::disk('public')->assertMissing($user->avatar);
});

test('can restore deleted a user', function () {
    $user = User::factory()->create();
    $user->delete();

    $service = app(UserService::class);
    $permanentlyDeletedUser = $service->restore($user->id);

    expect($permanentlyDeletedUser)->toBeInstanceOf(User::class);
    expect(User::find($user->id))->toBeInstanceOf(User::class);
    Storage::disk('public')->assertExists($user->avatar);
});

test("can sync user addresses", function () {
    $user = User::factory()->create();
    $service = app(UserService::class);

    $addresses = $service->syncAddresses($user, UserAddress::factory(5)->make()->toArray());
    expect($addresses->count())->toBe(5);

    $deletedAddress = $addresses->pop();
    $addresses->first()->address = "Updated";
    $addresses = $addresses->map(fn ($addr) => ['address' => $addr->address, 'id' => $addr->id]);
    $addresses->push(['address' => "New"]);
    $addresses = $addresses->toArray();
    $addresses = $service->syncAddresses($user, $addresses);
    expect($addresses->first()->address)->toBe('Updated');
    expect($addresses->last()->address)->toBe('New');
    expect(UserAddress::find($deletedAddress->id))->toBeNull();
});
