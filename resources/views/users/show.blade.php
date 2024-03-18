<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            User / {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:p-6 lg:p-8 bg-white shadow-lg rounded-sm grid grid-cols-3 gap-6">
            <div class="col-span-3 p-3 flex gap-6 items-center">
                @if ($user->avatarUrl)
                    <div class="wrapper h-[100px] w-[100px] overflow-hidden rounded-full">
                        <img src="{{ $user->avatarUrl }}" alt="avatar" class="w-[100px] h-[100px]">
                    </div>
                @endif
                <div>
                    <div class="detail flex gap-2 items-center">
                        <div class="label text-gray-700 font-bold">Name: </div>
                        <div class="value text-gray-600 text-[18px]">{{ $user->name }}</div>
                    </div>

                    <div class="detail flex gap-2 items-center">
                        <div class="label text-gray-700 font-bold">Email: </div>
                        <div class="value text-gray-600 text-[18px]">{{ $user->email }}</div>
                    </div>
                </div>
            </div>

            @foreach ($user->addresses as $address)
                <div class="detail bg-gray-100 p-3 rounded-md shadow-md">
                    <div class="label text-gray-700 font-bold">Address {{ $loop->index + 1 }}</div>
                    <div class="value text-gray-600 text-[18px]">{{ $address->address }}</div>
                </div>
            @endforeach
            <div class="btn col-span-3 flex justify-end gap-4">
                <a class="px-4 py-2 text-md text-gray-50 outline-[0px] block w-[100px] text-center bg-yellow-300 rounded-sm"
                    href="{{ route('users.edit', ['user' => $user]) }}">Edit</a>

                <form method="post" action="{{ route('users.destroy', ['user' => $user]) }}">
                    @csrf
                    @method('delete')

                    <input
                        class="px-4 py-2 text-md text-gray-50 block w-[100px] text-center bg-red-500 rounded-sm cursor-pointer"
                        type="submit" value="Delete">
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script></script>
