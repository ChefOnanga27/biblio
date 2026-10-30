@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border border-gray-200 bg-[#fafaf9] rounded-lg px-3.5 py-2.5 text-sm text-gray-800 outline-none transition focus:border-[#f97316] focus:bg-white disabled:opacity-50']) !!}>
