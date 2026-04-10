@props(['checked' => false, 'label' => "", "name" => "", "live" => false])
<div class="flex">
    <label class="flex items-center mt-0 md:mt-3">
        <input name="{{ $name }}" id="{{ $name }}"
               wire:model{{ ($live)?"":".defer" }}="{{ $name }}"
               type="checkbox" {{ $attributes->merge(['class' => 'form-checkbox h-5 w-5 text--500 border-2 focus:shadow-none ']) }}>
        <span class="block text-base leading-5 text-gray-400 sm:mt-px ml-2">
            {{ $label }}
            @error($name)
            <span class="block text-red-600 text-size-10-standart">Поле обязательно для заполнения</span>
            @enderror
        </span>
    </label>
</div>
