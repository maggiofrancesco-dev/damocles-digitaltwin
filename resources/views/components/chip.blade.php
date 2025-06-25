<span
    {{ $attributes->merge([
        'class' =>
            'inline-flex line-clamp-1 whitespace-nowrap items-center px-3 py-1 rounded-full text-white text-sm bg-sky-800 border border-transparent rounded-full font-semibold text-xs text-white uppercase',
    ]) }}>
    {{ $slot }}
</span>
