@props(['active'])

@php
$classes = ($active ?? false)
            ? 'text-blue-600 transition' // Class khi đang active
            : 'text-black hover:text-white transition'; // Class bình thường
@endphp

<li>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>
