<x-layouts.app :title="__('messages.home.hero_title')">

<!-- 1. Cabecera (Hero Section - Optimizado para Mobile Marketing) -->
<section class="hero-section">
    <div style="position: absolute; top: -60px; right: -60px; width: 450px; height: 450px; background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
    <div style="position: absolute; bottom: -80px; left: -80px; width: 380px; height: 380px; background: radial-gradient(circle, rgba(6, 95, 70, 0.4) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

    <div class="container" style="position: relative; z-index: 10; text-align: center; max-width: 920px;">
        <!-- Badges de Confianza y Autoridad -->
        <div class="hero-badges-row">
            <span class="hero-pill">
                <x-icon name="ball" size="14" /> {{ __('messages.home.hero_tag') }}
            </span>
            <span class="hero-pill" style="background: rgba(16, 185, 129, 0.25); border-color: rgba(52, 211, 153, 0.4); color: #a7f3d0;">
                <span class="pulse-dot"></span> WebSockets en Tiempo Real
            </span>
            <span class="hero-pill">
                ⚡ 100% Mobile First
            </span>
            <a href="{{ route('seguridad.certificado') }}" class="hero-pill" style="background: rgba(16, 185, 129, 0.25); border-color: rgba(52, 211, 153, 0.5); color: #a7f3d0; text-decoration: none;">
                <x-icon name="shield" size="14" /> Auditoría de Seguridad Aprobada
            </a>
        </div>

        <!-- Titular Principal -->
        <h1 class="hero-title">
            {{ __('messages.home.hero_title') }}
        </h1>

        <!-- Subtítulo -->
        <p class="hero-subtitle">
            {{ __('messages.home.hero_subtitle') }}
        </p>

        <!-- CTA Principal Llamativo y Buscador Directo -->
        <div style="display: flex; flex-direction: column; align-items: center; gap: 16px; margin-bottom: 28px;">
            <a href="#canchas-populares" class="btn btn-primary" style="padding: 16px 36px; font-size: 1.15rem; font-weight: 800; border-radius: 9999px; box-shadow: 0 12px 28px rgba(16, 185, 129, 0.4); text-transform: uppercase; letter-spacing: 0.5px;">
                <x-icon name="calendar" size="20" /> {{ __('messages.home.cta_primary') }}
            </a>

            <!-- Buscador Rápido -->
            <form action="{{ route('home') }}" method="GET" style="background: white; padding: 6px; border-radius: var(--radius-lg); box-shadow: 0 15px 35px rgba(0,0,0,0.3); display: flex; gap: 8px; width: 100%; max-width: 640px; flex-wrap: wrap;">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('messages.home.search_placeholder') }}" style="flex: 1; min-width: 240px; padding: 12px 18px; border: none; outline: none; font-size: 0.95rem; border-radius: var(--radius-md);">
                <button type="submit" class="btn btn-primary" style="padding: 12px 22px; font-weight: 700;">
                    <x-icon name="search" /> {{ __('messages.home.search_button') }}
                </button>
            </form>
        </div>

        <!-- Métricas de Impacto -->
        <div style="display: flex; justify-content: center; gap: clamp(16px, 4vw, 36px); margin-top: 36px; flex-wrap: wrap;">
            <div style="text-align: center;">
                <div style="font-size: clamp(1.6rem, 3.5vw, 2.2rem); font-weight: 800; color: #a7f3d0;">{{ $totalEstablecimientos }}</div>
                <div style="font-size: 0.8rem; color: #cbd5e1; text-transform: uppercase; font-weight: 600;">{{ __('messages.home.stats_complexes') }}</div>
            </div>
            <div style="width: 1px; background: rgba(255,255,255,0.2); height: 38px;"></div>
            <div style="text-align: center;">
                <div style="font-size: clamp(1.6rem, 3.5vw, 2.2rem); font-weight: 800; color: #a7f3d0;">{{ $totalCanchas }}</div>
                <div style="font-size: 0.8rem; color: #cbd5e1; text-transform: uppercase; font-weight: 600;">{{ __('messages.home.stats_pitches') }}</div>
            </div>
            <div style="width: 1px; background: rgba(255,255,255,0.2); height: 38px;"></div>
            <div style="text-align: center;">
                <div style="font-size: clamp(1.6rem, 3.5vw, 2.2rem); font-weight: 800; color: #a7f3d0;">&lt; 60 seg</div>
                <div style="font-size: 0.8rem; color: #cbd5e1; text-transform: uppercase; font-weight: 600;">Tiempo de Reserva</div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Cómo Funciona (3 Pasos Visuales Simples) -->
<section id="como-funciona" style="background: #ffffff; padding: clamp(48px, 6vw, 72px) 20px; border-bottom: 1px solid var(--border);">
    <div class="container" style="text-align: center;">
        <span style="color: var(--primary); font-weight: 800; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 1px;">
            Flujo Logístico Simple &amp; Sin Fricción
        </span>
        <h2 style="font-size: clamp(1.8rem, 3vw, 2.4rem); margin-top: 6px; margin-bottom: 10px;">
            {{ __('messages.home.how_it_works_title') }}
        </h2>
        <p style="color: var(--text-muted); max-width: 620px; margin: 0 auto;">
            {{ __('messages.home.how_it_works_subtitle') }}
        </p>

        <div class="steps-grid">
            <!-- Paso 1 -->
            <div class="step-card">
                <span class="step-number-badge">1</span>
                <div class="step-icon-wrap">
                    <x-icon name="building" size="28" />
                </div>
                <h3 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 10px; color: var(--text-main);">
                    {{ __('messages.home.step1_title') }}
                </h3>
                <p style="color: var(--text-muted); font-size: 0.92rem; line-height: 1.6;">
                    {{ __('messages.home.step1_desc') }}
                </p>
                <div style="margin-top: auto; padding-top: 14px; font-size: 0.8rem; color: var(--primary); font-weight: 700;">
                    ✓ Césped F5 / F7 / Techada
                </div>
            </div>

            <!-- Paso 2 -->
            <div class="step-card">
                <span class="step-number-badge">2</span>
                <div class="step-icon-wrap">
                    <x-icon name="dollar" size="28" />
                </div>
                <h3 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 10px; color: var(--text-main);">
                    {{ __('messages.home.step2_title') }}
                </h3>
                <p style="color: var(--text-muted); font-size: 0.92rem; line-height: 1.6;">
                    {{ __('messages.home.step2_desc') }}
                </p>
                <div style="margin-top: auto; padding-top: 14px; font-size: 0.8rem; color: var(--primary); font-weight: 700;">
                    ✓ Yape • Plin • IziPay • Niubiz • Efectivo
                </div>
            </div>

            <!-- Paso 3 -->
            <div class="step-card">
                <span class="step-number-badge">3</span>
                <div class="step-icon-wrap">
                    <x-icon name="ball" size="28" />
                </div>
                <h3 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 10px; color: var(--text-main);">
                    {{ __('messages.home.step3_title') }}
                </h3>
                <p style="color: var(--text-muted); font-size: 0.92rem; line-height: 1.6;">
                    {{ __('messages.home.step3_desc') }}
                </p>
                <div style="margin-top: auto; padding-top: 14px; font-size: 0.8rem; color: var(--primary); font-weight: 700;">
                    ✓ Comprobante Digital con Código
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Catálogo E-Commerce (Tarjetas Dinámicas de Canchas Populares) -->
<section id="canchas-populares" style="background: #f8fafc; padding: clamp(48px, 6vw, 72px) 20px;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
            <div>
                <span style="color: var(--primary); font-weight: 800; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 1px;">
                    Catálogo E-Commerce de Servicios
                </span>
                <h2 style="font-size: clamp(1.8rem, 3vw, 2.4rem); margin-top: 4px;">
                    {{ __('messages.home.catalog_popular_title') }}
                </h2>
                <p style="color: var(--text-muted); max-width: 600px;">
                    {{ __('messages.home.catalog_popular_subtitle') }}
                </p>
            </div>
            <a href="#todos-complejos" class="btn btn-outline" style="border-radius: 9999px;">
                Ver Todos los Complejos <x-icon name="arrow-right" size="14" />
            </a>
        </div>

        <!-- Grid de Canchas Populares E-Commerce -->
        <div class="popular-pitches-grid">
            @forelse($canchasPopulares as $popular)
                <div class="pitch-ecommerce-card">
                    <!-- Thumbnail y Badges -->
                    <div class="pitch-thumb">
                        <div class="pitch-thumb-turf-pattern"></div>
                        <span class="pitch-type-badge">
                            @if($popular->capacidad <= 10)
                                Fútbol 5 (F5)
                            @elseif($popular->capacidad <= 14)
                                Fútbol 7 (F7)
                            @else
                                Fútbol 8 / Principal
                            @endif
                        </span>
                        <span class="pitch-live-badge">
                            <span class="pulse-dot"></span> En Vivo
                        </span>
                        <div style="text-align: center; position: relative; z-index: 5;">
                            <x-icon name="ball" size="42" style="opacity: 0.9;" />
                            <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-top: 4px; color: #a7f3d0;">
                                Gras Sintético Monofilamento
                            </div>
                        </div>
                    </div>

                    <!-- Cuerpo de la Tarjeta E-Commerce -->
                    <div class="pitch-body">
                        <div style="font-size: 0.8rem; color: #059669; font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; gap: 4px;">
                            <x-icon name="building" size="13" /> {{ $popular->establecimiento->nombre }}
                        </div>

                        <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                            {{ $popular->nombre }}
                        </h3>

                        <p style="font-size: 0.85rem; color: #64748b; line-height: 1.4; margin-bottom: 14px; display: flex; align-items: flex-start; gap: 5px;">
                            <x-icon name="map-pin" size="14" style="flex-shrink: 0; margin-top: 2px;" />
                            <span>{{ $popular->establecimiento->direccion }}</span>
                        </p>

                        <!-- Especificaciones Rápidas -->
                        <div style="display: flex; gap: 10px; margin-bottom: 18px; font-size: 0.8rem; color: #475569; flex-wrap: wrap;">
                            <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px;">
                                <x-icon name="users" size="12" /> Capacidad: <strong>{{ $popular->capacidad }} jug.</strong>
                            </span>
                            <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px;">
                                <x-icon name="clock" size="12" /> Turnos: <strong>60 min</strong>
                            </span>
                        </div>

                        <!-- Precio y Botón CTA E-Commerce -->
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                            <div>
                                <span style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Tarifa Oficial</span>
                                <div style="font-size: 1.35rem; font-weight: 900; color: var(--primary);">
                                    S/. {{ number_format($popular->precio_hora, 2) }}
                                    <span style="font-size: 0.75rem; font-weight: 500; color: #64748b;">/ h</span>
                                </div>
                            </div>
                            <a href="{{ route('canchas.detalle', $popular->id) }}" class="btn btn-primary" style="padding: 10px 18px; font-weight: 700; border-radius: 8px;">
                                {{ __('messages.home.book_now_btn') }} <x-icon name="arrow-right" size="14" />
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card" style="text-align: center; padding: 40px; grid-column: 1 / -1;">
                    <p style="color: var(--text-muted);">No hay canchas registradas en este momento.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Catálogo Completo por Complejos Deportivos -->
<section id="todos-complejos" class="container" style="padding-top: 40px; padding-bottom: 60px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h2 style="font-size: 1.8rem;">{{ __('messages.home.catalog_title') }}</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">{{ __('messages.home.catalog_subtitle') }}</p>
        </div>
        @if(request('q'))
            <a href="{{ route('home') }}" class="btn btn-outline btn-sm">{{ __('messages.home.clear_search') }}</a>
        @endif
    </div>

    @if($establecimientos->isEmpty())
        <div class="card" style="text-align: center; padding: 50px 20px;">
            <div style="margin-bottom: 12px;"><x-icon name="building" size="48" style="color: var(--text-muted);" /></div>
            <h3>{{ __('messages.home.no_complexes') }}</h3>
            <p style="color: var(--text-muted); margin-top: 6px;">{{ __('messages.home.try_other_search') }}</p>
            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 18px;">{{ __('messages.home.view_all') }}</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 24px;">
            @foreach($establecimientos as $est)
                <div class="card" style="display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--border);">
                    <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; padding: 20px; border-radius: var(--radius-md) var(--radius-md) 0 0; margin: -24px -24px 20px -24px;">
                        <span style="font-size: 0.75rem; background: var(--primary); padding: 3px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase;">
                            Coracora • Gras Sintético
                        </span>
                        <h3 style="color: white; margin-top: 8px; font-size: 1.3rem;">{{ $est->nombre }}</h3>
                        <p style="font-size: 0.85rem; color: #cbd5e1; margin-top: 4px;">
                            <x-icon name="map-pin" size="14" /> {{ $est->direccion }}
                            @if($est->referencia)
                                <br><small style="color: #94a3b8;">Ref: {{ $est->referencia }}</small>
                            @endif
                        </p>
                    </div>

                    <p style="color: #475569; font-size: 0.9rem; margin-bottom: 18px; line-height: 1.5; flex: 1;">
                        {{ Str::limit($est->descripcion, 120, '...') }}
                    </p>

                    <!-- Lista de canchas de este complejo -->
                    <div style="background: #f8fafc; padding: 12px; border-radius: var(--radius-sm); margin-bottom: 18px; border: 1px solid #f1f5f9;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">
                            {{ __('messages.home.pitches_in_complex') }}
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @forelse($est->canchas as $cancha)
                                <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    <div>
                                        <strong style="font-size: 0.9rem; color: #1e293b;">{{ $cancha->nombre }}</strong>
                                        <div style="font-size: 0.78rem; color: #64748b;"><x-icon name="users" size="13" /> {{ $cancha->capacidad }} {{ __('messages.home.players_capacity') }}</div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem;">
                                            S/. {{ number_format($cancha->precio_hora, 2) }}
                                        </div>
                                        <span style="font-size: 0.72rem; color: #94a3b8;">/ {{ __('messages.home.per_hour') }}</span>
                                    </div>
                                </div>
                            @empty
                                <div style="font-size: 0.85rem; color: #94a3b8;">{{ __('messages.home.no_active_pitches') }}</div>
                            @endforelse
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: auto;">
                        <a href="{{ route('canchas.establecimiento', $est->id) }}" class="btn btn-primary" style="flex: 1; text-align: center;">
                            <x-icon name="calendar" /> {{ __('messages.home.see_pitches_and_book') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 30px;">
            {{ $establecimientos->links() }}
        </div>
    @endif
</section>

<!-- 4. Prueba Social (Testimonios y Reseñas B2C) -->
<section style="background: #ffffff; padding: clamp(48px, 6vw, 72px) 20px; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
    <div class="container" style="text-align: center;">
        <span style="color: var(--primary); font-weight: 800; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 1px;">
            Prueba Social &amp; Inbound Marketing
        </span>
        <h2 style="font-size: clamp(1.8rem, 3vw, 2.4rem); margin-top: 4px; margin-bottom: 8px;">
            {{ __('messages.home.social_proof_title') }}
        </h2>
        <p style="color: var(--text-muted); max-width: 620px; margin: 0 auto 36px;">
            {{ __('messages.home.social_proof_subtitle') }}
        </p>

        <div class="testimonials-grid">
            <!-- Testimonio 1 (Exact Copy del requerimiento) -->
            <div class="testimonial-card">
                <div>
                    <div class="star-rating">★★★★★</div>
                    <p class="testimonial-quote">
                        "{{ __('messages.home.review1_quote') }}"
                    </p>
                </div>
                <div class="testimonial-author-wrap">
                    <div class="testimonial-avatar">CM</div>
                    <div style="text-align: left;">
                        <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">{{ __('messages.home.review1_author') }}</div>
                        <div style="font-size: 0.8rem; color: #64748b;">{{ __('messages.home.review1_role') }}</div>
                    </div>
                </div>
            </div>

            <!-- Testimonio 2 -->
            <div class="testimonial-card">
                <div>
                    <div class="star-rating">★★★★★</div>
                    <p class="testimonial-quote">
                        "{{ __('messages.home.review2_quote') }}"
                    </p>
                </div>
                <div class="testimonial-author-wrap">
                    <div class="testimonial-avatar" style="background: #065f46;">DH</div>
                    <div style="text-align: left;">
                        <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">{{ __('messages.home.review2_author') }}</div>
                        <div style="font-size: 0.8rem; color: #64748b;">{{ __('messages.home.review2_role') }}</div>
                    </div>
                </div>
            </div>

            <!-- Testimonio 3 -->
            <div class="testimonial-card">
                <div>
                    <div class="star-rating">★★★★★</div>
                    <p class="testimonial-quote">
                        "{{ __('messages.home.review3_quote') }}"
                    </p>
                </div>
                <div class="testimonial-author-wrap">
                    <div class="testimonial-avatar" style="background: #0284c7;">RQ</div>
                    <div style="text-align: left;">
                        <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a;">{{ __('messages.home.review3_author') }}</div>
                        <div style="font-size: 0.8rem; color: #64748b;">{{ __('messages.home.review3_role') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. Certificado de Auditoría de Seguridad & Confianza Digital -->
<section id="certificado-auditoria" style="background: #ffffff; padding: clamp(48px, 6vw, 76px) 20px; border-bottom: 1px solid var(--border);">
    <div class="container">
        <div style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%); border-radius: 20px; padding: clamp(28px, 5vw, 44px); color: white; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(6, 78, 59, 0.25);">
            <!-- Efectos de luz -->
            <div style="position: absolute; top: -60px; right: -60px; width: 320px; height: 320px; background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
            <div style="position: absolute; bottom: -80px; left: -80px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(5, 150, 105, 0.2) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

            <div style="position: relative; z-index: 10; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px; align-items: center;">
                <!-- Columna Izquierda: Información del Certificado -->
                <div>
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(52, 211, 153, 0.4); padding: 5px 14px; border-radius: 9999px; font-size: 0.8rem; font-weight: 800; color: #6ee7b7; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">
                        <x-icon name="shield" size="14" /> Auditoría de Seguridad Aprobada
                    </div>

                    <h2 style="font-size: clamp(1.8rem, 3.5vw, 2.4rem); color: #ffffff; line-height: 1.25; margin-bottom: 14px; font-weight: 900;">
                        Certificado de Auditoría de Seguridad &amp; Confianza Digital
                    </h2>

                    <p style="color: #cbd5e1; font-size: 0.96rem; line-height: 1.6; margin-bottom: 24px;">
                        Tu tranquilidad es nuestra prioridad. GraCitas ha sido auditada exhaustivamente con <strong>0 vulnerabilidades críticas</strong>, garantizando protección de datos personales (Ley N° 29733), prevención absoluta de sobreventas con bloqueo pesimista y comprobantes blindados contra accesos no autorizados.
                    </p>

                    <!-- Lista de 4 Garantías Auditadas -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 28px; font-size: 0.88rem;">
                        <div style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #34d399; font-weight: 800; font-size: 1.1rem; line-height: 1;">✓</span>
                            <div>
                                <strong style="color: white; display: block;">Control IDOR Blindado</strong>
                                <span style="color: #94a3b8; font-size: 0.8rem;">Comprobantes solo visibles por su titular</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #34d399; font-weight: 800; font-size: 1.1rem; line-height: 1;">✓</span>
                            <div>
                                <strong style="color: white; display: block;">Anti-Overbooking Pesimista</strong>
                                <span style="color: #94a3b8; font-size: 0.8rem;">Cero colisiones de horario en cancha</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #34d399; font-weight: 800; font-size: 1.1rem; line-height: 1;">✓</span>
                            <div>
                                <strong style="color: white; display: block;">Privacidad de Disponibilidad</strong>
                                <span style="color: #94a3b8; font-size: 0.8rem;">Códigos de reserva 100% protegidos</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: 10px;">
                            <span style="color: #34d399; font-weight: 800; font-size: 1.1rem; line-height: 1;">✓</span>
                            <div>
                                <strong style="color: white; display: block;">Cifrado JWT &amp; Rate Limiting</strong>
                                <span style="color: #94a3b8; font-size: 0.8rem;">Mitigación activa de fuerza bruta</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="{{ route('seguridad.certificado') }}" class="btn btn-primary" style="padding: 12px 26px; font-size: 0.95rem; font-weight: 800; border-radius: 9999px; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);">
                            <x-icon name="shield" size="16" /> Ver Certificado Oficial
                        </a>
                        <a href="{{ route('seguridad.auditoria') }}" target="_blank" class="btn btn-outline" style="border-color: rgba(255,255,255,0.3); color: white; border-radius: 9999px; padding: 12px 22px; font-size: 0.95rem;">
                            <x-icon name="download" size="16" /> Descargar Informe PDF
                        </a>
                    </div>
                </div>

                <!-- Columna Derecha: Sello Gráfico de Certificación -->
                <div style="display: flex; justify-content: center;">
                    <div style="background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(52, 211, 153, 0.4); border-radius: 20px; padding: 28px 24px; text-align: center; max-width: 320px; width: 100%; backdrop-filter: blur(10px); box-shadow: 0 15px 35px rgba(0,0,0,0.3);">
                        <div style="width: 72px; height: 72px; margin: 0 auto 14px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4); border: 3px solid rgba(255,255,255,0.2);">
                            <x-icon name="shield" size="36" />
                        </div>
                        <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; color: #a7f3d0; font-weight: 800;">
                            SELLO DE AUDITORÍA
                        </div>
                        <div style="font-size: 1.35rem; font-weight: 900; color: #ffffff; margin-top: 4px; margin-bottom: 8px;">
                            GraCitas Verified
                        </div>
                        <div style="display: inline-block; background: #10b981; color: #064e3b; font-weight: 900; font-size: 0.9rem; padding: 3px 14px; border-radius: 9999px; margin-bottom: 14px;">
                            CALIFICACIÓN A+
                        </div>
                        <div style="border-top: 1px solid rgba(255,255,255,0.12); padding-top: 12px; font-size: 0.78rem; color: #cbd5e1; line-height: 1.5;">
                            <div><strong>Código:</strong> CERT-SEC-2026-A1</div>
                            <div><strong>Riesgos Críticos:</strong> 0 detectados</div>
                            <div><strong>Fecha:</strong> Noviembre 2026</div>
                        </div>
                        <a href="{{ route('seguridad.certificado') }}" style="display: block; margin-top: 14px; font-size: 0.82rem; color: #34d399; font-weight: 700; text-decoration: underline;">
                            Verificar validez técnica →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. Captación B2B (Sección para Dueños de Canchas / Partners) -->
<section style="background: #f8fafc; padding: clamp(48px, 6vw, 80px) 20px;">
    <div class="container">
        <div class="b2b-banner">
            <div style="position: absolute; right: -40px; bottom: -40px; width: 240px; height: 240px; background: radial-gradient(circle, rgba(16, 185, 129, 0.25) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

            <div style="max-width: 720px; position: relative; z-index: 10;">
                <span style="display: inline-block; background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(52, 211, 153, 0.35); color: #6ee7b7; padding: 4px 14px; border-radius: 9999px; font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">
                    Alianza B2B • Marketplace Deportivo
                </span>

                <h2 style="font-size: clamp(1.8rem, 3.5vw, 2.6rem); color: #ffffff; line-height: 1.2; margin-bottom: 14px;">
                    {{ __('messages.home.b2b_title') }}
                </h2>

                <p style="font-size: clamp(1.05rem, 2vw, 1.25rem); color: #a7f3d0; font-weight: 700; margin-bottom: 14px;">
                    {{ __('messages.home.b2b_subtitle') }}
                </p>

                <p style="color: #cbd5e1; font-size: 0.95rem; line-height: 1.6; margin-bottom: 28px;">
                    {{ __('messages.home.b2b_desc') }}
                </p>

                <!-- Beneficios B2B en formato lista -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 32px; font-size: 0.88rem; color: #e2e8f0;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #34d399;">✔</span> Alertas WebSockets en tu celular
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #34d399;">✔</span> Control de caja y cobros presenciales
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #34d399;">✔</span> Sin comisiones bancarias abusivas
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="color: #34d399;">✔</span> Reportes de horas pico y ocupación
                    </div>
                </div>

                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <a href="{{ route('register') }}?rol=propietario" class="btn btn-primary" style="padding: 14px 32px; font-size: 1.05rem; font-weight: 800; border-radius: 9999px; box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);">
                        <x-icon name="building" size="18" /> {{ __('messages.home.b2b_cta') }}
                    </a>
                    <a href="https://wa.me/51966123456?text=Hola%2C%20soy%20due%C3%B1o%20de%20una%20cancha%20en%20Coracora%20y%20deseo%20afiliarme" target="_blank" rel="noopener" class="btn btn-outline" style="border-color: rgba(255,255,255,0.3); color: white; border-radius: 9999px; padding: 14px 26px;">
                        Consultar por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>
