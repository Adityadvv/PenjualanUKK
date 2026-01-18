@props(['value'])

<label {{ $attributes->merge(['class' => 'text-sm font-medium text-gray']) }}>
    {{ $value ?? $slot }}
</label>