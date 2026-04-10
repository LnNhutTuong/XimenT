<button {{ $attributes->merge(['class' => 'btn-base']) }}>
    <span class="btn-text">{{ $slot }}</span>
    <span class="btn-underline {{ $color }}"></span>
</button>