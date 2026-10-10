<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Laravel' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- Flux lee "flux.appearance" y, si no existe, quita la clase "dark" del <html>.
     Fijamos "dark" como valor inicial para que el panel sea legible desde el
     primer pintado, sin depender de que el usuario configure su apariencia. --}}
<script>
    window.localStorage.setItem('flux.appearance', 'dark');
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
