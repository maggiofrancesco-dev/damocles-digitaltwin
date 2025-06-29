@props(['name', 'value', 'id' => null, 'checked' => false, 'label' => ''])

<label class="inline-flex items-center space-x-2">
    <input required type="radio" name="{{ $name }}" value="{{ $value }}"
        id="{{ $id ?? $name . '-' . $value }}"
        {{ $attributes->merge(['class' => 'text-sky-600 border-gray-300 focus:ring-sky-800']) }}>
    <span {{ $attributes->merge(['class' => 'block font-medium text-sm text-sky-700']) }}>
        {{ $label ?: ucfirst($value) }}
    </span>
</label>
