<x-storefront.layout title="Contacto" description="Escríbenos: respondemos el mismo día." active="contact">
    <div class="container page">
        <section aria-labelledby="contact-title">
            <div class="contact__intro">
                <p class="eyebrow">Contacto</p>
                <h1 id="contact-title" class="title-display title-page">Estamos para ayudarte</h1>
                <p class="lead">Escríbenos y te respondemos el mismo día hábil.</p>
            </div>

            <div class="contact__grid">
                <div class="contact__list">
                    <article class="contact-card">
                        <div class="contact-card__icon">
                            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        </div>
                        <div>
                            <h2 class="contact-card__title">Correo</h2>
                            <p class="contact-card__value">hola@azul.tienda</p>
                        </div>
                    </article>

                    <article class="contact-card">
                        <div class="contact-card__icon">
                            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a12 12 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/></svg>
                        </div>
                        <div>
                            <h2 class="contact-card__title">Teléfono</h2>
                            <p class="contact-card__value">+51 999 000 000</p>
                        </div>
                    </article>

                    <article class="contact-card">
                        <div class="contact-card__icon">
                            <svg class="icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        </div>
                        <div>
                            <h2 class="contact-card__title">Horario</h2>
                            <p class="contact-card__value">Lunes a sábado, 9:00–19:00</p>
                        </div>
                    </article>
                </div>

                <form class="contact-form" method="post" action="{{ route('contact.store') }}">
                    @csrf

                    <div class="contact-form__fields">
                        @if (session('status'))
                            <p class="text-success">{{ session('status') }}</p>
                        @endif

                        <label class="field">
                            <span class="field__label">Nombre</span>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" autocomplete="name" required class="field__control">
                            @error('nombre')
                                <p class="text-danger text-sm">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="field">
                            <span class="field__label">Correo</span>
                            <input type="email" name="correo" value="{{ old('correo') }}" autocomplete="email" required class="field__control">
                            @error('correo')
                                <p class="text-danger text-sm">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="field">
                            <span class="field__label">Mensaje</span>
                            <textarea name="mensaje" rows="5" required class="field__control">{{ old('mensaje') }}</textarea>
                            @error('mensaje')
                                <p class="text-danger text-sm">{{ $message }}</p>
                            @enderror
                        </label>

                        <button @class(['btn', 'btn--primary', 'btn--block']) type="submit">
                            Enviar mensaje
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</x-storefront.layout>
