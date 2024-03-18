<div x-data="{ imagePreview: '{{ $image ?? null}}' }" class="relative">
    <div x-show="imagePreview">
        <img :src="imagePreview" alt="Image Preview" style="max-width: 200px;">
    </div>
    <label for="image-input"
        class="cursor-pointer w-full h-40px my-2 bg-slate-600 rounded block p-2 text-center text-white">Upload
        Avatar</label>
    <input
        {{ $attributes->merge(['type' => 'file', 'accept' => 'image/*', 'id' => 'image-input', 'class' => 'w-[0px] h-[0px] absolute']) }}
        @change="imagePreview = URL.createObjectURL($event.target.files[0])">

</div>
