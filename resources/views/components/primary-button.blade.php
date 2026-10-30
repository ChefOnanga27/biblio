<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-[#063537] border border-[#063537] rounded-lg font-semibold text-[13px] text-white cursor-pointer transition hover:bg-[#f97316] hover:border-[#f97316] focus:outline-none disabled:opacity-50']) }}>
    {{ $slot }}
</button>
