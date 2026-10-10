<div class="settings-layout">
    <div class="settings-layout__nav">
        <flux:navlist>
            <flux:navlist.item href="{{ route('settings.profile') }}" wire:navigate>Profile</flux:navlist.item>
            <flux:navlist.item href="{{ route('settings.password') }}" wire:navigate>Password</flux:navlist.item>
            <flux:navlist.item href="{{ route('settings.appearance') }}" wire:navigate>Appearance</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="settings-layout__content">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="settings-layout__slot">
            {{ $slot }}
        </div>
    </div>
</div>
