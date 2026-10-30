<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-white border border-gray-200 rounded-lg font-semibold text-[13px] text-gray-600 cursor-pointer transition hover:border-[#063537] hover:text-[#063537] focus:outline-none disabled:opacity-25']) }}>
    {{ $slot }}
</button>
