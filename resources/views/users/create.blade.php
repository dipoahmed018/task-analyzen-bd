<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User / Create
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:p-6 lg:p-8 space-y-2 bg-white shadow-lg rounded-md">
            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                @csrf


                <x-image-input name="avatar" />
                <x-input-error :messages="$errors->get('avatar')" class="mt-2" />

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div x-data="{ addresses_count: 0 }">
                    <template x-for="(adress_no, index) in addresses_count">
                        <div class="address-wrapper mt-4">
                            <x-input-label>Address <span x-text="index + 1"></span> </x-input-label>
                            <x-text-input class="block mt-1 w-full" type="text" x-bind:name="'addresses[' + index + '][address]'"
                                autocomplete="address" maxlength="251" placeholder="Max 251 character is allowed    "/>
                        </div>
                    </template>
                    <x-secondary-button class="mt-4" @click="addresses_count += 1">
                        Add Address
                    </x-secondary-button>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-4">
                        Create
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
