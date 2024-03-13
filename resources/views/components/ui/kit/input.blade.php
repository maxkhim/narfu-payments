@props(
[
    'disabled' => false,
    'name' => 'default_name',
    'label' => "",
    "placeholder" => "",
    "hint" => "",
    "mustBeFilled" => false,
])
<div>
    @if ($label)
    <label class="block font-medium text text-gray-700 mt-5" for="{{ $name }}">
    {{ $label }}
        @if ($mustBeFilled)
            <sup class="text-red-500">*</sup>
        @endif
    </label>
    @endif
    @php
        $markedWarning = false;
    @endphp
{{--    @error($name)--}}
{{--        @php--}}
{{--            $markedWarning = true;--}}
{{--        @endphp--}}
{{--    @enderror--}}

    <input class="form-input rounded-md shadow-sm mt-1 block w-full @if($markedWarning) bg-red-100 border-red-500 @endif"
           name="{{ $name }}"
           id="{{ $name }}"
           type="text"
           placeholder="{{ $placeholder }}"
           wire:model="{{ $name }}"/>
    @error($name)
        <div class="text-red-600 text-size-10-standart">{{ $message }}</div>
    @enderror
    @if ($hint)
        <div class="text-gray-400 text-size-10-standart">{{ $hint }}</div>
    @endif
</div>