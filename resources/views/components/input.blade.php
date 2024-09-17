@props([
    'label' => false,
    'labelText' => 'Label Text',
    'type' => 'text',
    'name' => '',
    'textColor' => '',
    'error' => false,
    'value' => '',
])

<div class="flex flex-col w-full gap-2 text-start text-{{ $textColor }}">
    @if ($label)
        <label class="text-md" for="{{ $name }}">{{ $labelText }}</label>
    @endif

    <input
    id="{{ $name }}"
    name="{{ $name }}"
    type="{{ $type }}"
    value="{{ $value }}"
    class="first-letter:w-full px-3 py-2.5
    text-md text-zinc-600 shadow-md
    bg-transparent rounded-md transition
    border border-opacity-30
    focus:ring focus:ring-blue-600 focus:ring-opacity-40 focus:outline-none
    disabled:bg-gray-100"
    {{ $attributes }}
    />

    @if($error)
        @error($name)
        <p class="text-error">
            {{ $message }}
        </p>
        @enderror
    @endif
</div>
