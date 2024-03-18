@php
    $addresses = $user->addresses->each(fn($addr) => $addr->key = Str::random());
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User / Edit
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:p-6 lg:p-8 space-y-2 bg-white shadow-lg rounded-md">
            <form method="POST" action="{{ route('users.update', ['user' => $user]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <x-image-input name="avatar" :image="$user->avatarUrl" />
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name') ?: $user->name" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email') ?: $user->email" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div x-data='{ addresses: @json($addresses) }'>
                    <template x-for="(address, index) in addresses" :key="address.key">
                        <div
                            class="address-wrapper mt-4 grid grid-rows-[20px,42px] grid-cols-[1fr,25px] items-center gap-x-2">
                            <x-input-label class="col-span-2">Address <span x-text="index + 1"></span> </x-input-label>
                            <template x-if="address.id">
                                <input type="hidden" x-bind:name="'addresses[' + index + '][id]'"
                                    x-bind:value="address.id">
                            </template>
                            <x-text-input class="block mt-1 w-full" type="text"
                                x-bind:name="'addresses[' + index + '][address]'" autocomplete="address" maxlength="251"
                                placeholder="Max 251 character is allowed" x-model="address.address" />
                            <x-close-icon @click="addresses.splice(index, 1)" />
                        </div>
                    </template>
                    <x-secondary-button class="mt-4" @click="addresses.push({address: ''})">
                        Add Address
                    </x-secondary-button>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-4">
                        Save
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
