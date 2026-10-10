<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="page-body">
        <div class="page-body--centered">
            <div>
                <div></div>
                <a href="{{ route('home') }}" class="sidebar-layout__brand" wire:navigate>
                    <span class="auth-brand__mark">
                        <x-app-logo-icon class="auth-brand__logo" />
                    </span>
                    {{ config('app.name', 'Laravel') }}
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div>
                    <blockquote class="stack stack--tight">
                        <p>&ldquo;{{ trim($message) }}&rdquo;</p>
                        <footer class="text-sm">{{ trim($author) }}</footer>
                    </blockquote>
                </div>
            </div>
            <div>
                <div class="auth-shell">
                    <a href="{{ route('home') }}" class="auth-brand" wire:navigate>
                        <span class="auth-brand__mark">
                            <x-app-logo-icon class="auth-brand__logo" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
