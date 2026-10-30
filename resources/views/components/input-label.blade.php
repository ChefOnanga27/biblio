@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[11.5px] font-semibold uppercase tracking-wider text-gray-500']) }}>
    {{ $value ?? $slot }}
</label>
