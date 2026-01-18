@props(['disabled' => false])

<input {{ $attributes->merge([
    'class' => 'block mt-1 w-full rounded-full border border-white/60
                bg-gray/30 text-gray placeholder-gray/70
                px-4 py-3
                focus:border-gray focus:ring-white focus:ring-2'
]) }}>