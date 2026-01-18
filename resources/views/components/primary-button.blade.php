<button {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center px-4 py-3
                bg-white text-blue-700 font-bold rounded-full
                hover:bg-white/90 focus:outline-none focus:ring-2
                focus:ring-white focus:ring-offset-2'
]) }}>
    {{ $slot }}
</button>