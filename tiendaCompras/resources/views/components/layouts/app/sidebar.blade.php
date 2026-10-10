
@php
    $groups = [

        'platform' => [

            'heading' => 'Platform',
            'items' => [
                [
                    'icon' => 'home',
                    'name' => 'dashboard',
                    'route' => route('dashboard'),
                    'current' => request()->routeIs('dashboard'),
                    'url' => route('dashboard'),
                ],
            ]      

        ]

    ]
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="app-body">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="sidebar-layout__brand" wire:navigate>
                <x-app-logo class="sidebar-layout__logo" href="#"></x-app-logo>
            </a>

            <flux:navlist variant="outline">
                @foreach ($groups as $group)
                    <flux:navlist.group :heading="$group['heading']" class="grid">
                        @foreach ($group['items'] as $item)
                            <flux:navlist.item :icon="$item['icon']" :href="$item['url']" :current="$item['current']" wire:navigate>{{ $item['name'] }}</flux:navlist.item>
                        @endforeach
                    </flux:navlist.group>
                @endforeach
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="text-sm">
                            <div class="user-card">
                                <span class="avatar">
                                    <span>
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="user-card__info">
                                    <span class="user-card__name">{{ auth()->user()->name }}</span>
                                    <span class="user-card__email">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="text-sm">
                            <div class="user-card">
                                <span class="avatar">
                                    <span>
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="user-card__info">
                                    <span class="user-card__name">{{ auth()->user()->name }}</span>
                                    <span class="user-card__email">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
