<a
    {{ $attributes->merge(['class' => 'link']) }}
    wire:navigate
>
    {{ $slot }}
</a>
