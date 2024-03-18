<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trashed Users') }}

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-2">
            <div class="th flex text-gray-800 p-1">
                <div class="w-[20%]">Avatar</div>
                <div class="w-[30%]">Name</div>
                <div class="w-[40%]">Email</div>
                <div class="w-[10%]">Action</div>
            </div>
            @foreach ($users as $user)
                <div class="user bg-white shadow-sm p-1 flex text-gray-600 items-center">
                    <p class="w-[20%]">
                        @if ($user->avatarUrl)
                            <img src="{{ $user->avatarUrl }}" alt="avatar" class="h-[50px] w-[50px] rounded-full">
                        @endif
                    </p>
                    <p class="w-[30%] text-[14px]">{{ $user->name }}</p>
                    <p class="w-[40%] text-[14px]">{{ $user->email }}</p>
                    <div class="w-[10%]">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <form method="post" action="{{ route('delted-users.destroy', ['user' => $user->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <input
                                        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 cursor-pointer"
                                        type="submit" value="Permanently Delete">
                                </form>

                                <form method="post" action="{{ route('delted-users.restore', ['user' => $user->id]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input
                                        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 cursor-pointer"
                                        type="submit" value="Restore">
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-center m-3 gap-3 sm:px-6 lg:px-8">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>

<script></script>
