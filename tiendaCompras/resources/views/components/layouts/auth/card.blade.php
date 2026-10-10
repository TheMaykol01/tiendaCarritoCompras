<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="page-body">
        <div class="page-body--centered">
            <div class="auth-shell">
                <a href="{{ route('home') }}" class="auth-brand" wire:navigate>
                    <span class="auth-brand__mark">
                        <x-app-logo-icon class="auth-brand__logo" />
                    </span>

                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="stack">
                    <div class="card">
                        <div class="card__body">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
