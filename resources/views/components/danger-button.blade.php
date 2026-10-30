<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-red-600 border border-red-600 rounded-lg font-semibold text-[13px] text-white cursor-pointer transition hover:bg-red-500 active:bg-red-700 focus:outline-none']) }}>
    {{ $slot }}
</button>
