@props([
    'title',
    'description',
])

<div class="stack stack--tight text-center">
    <h1 class="text-strong">{{ $title }}</h1>
    <p class="text-center text-sm text-muted">{{ $description }}</p>
</div>
