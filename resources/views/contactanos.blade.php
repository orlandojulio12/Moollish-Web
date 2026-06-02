@extends('layouts.landing')

@section('title', 'Contacto | Moollish')

@push('styles')
<style>
    /* ── Scroll fix ──────────────────────────────────────────── */
    html, body {
        overflow-x: hidden;
        overflow-y: auto !important;
        height: auto !important;
        min-height: 100vh;
    }

    /* ── Hero banner ─────────────────────────────────────────── */
    .contact-hero {
        background: linear-gradient(135deg, #1a0800 0%, #3d1800 45%, #7a3d00 75%, #c47a0a 100%);
        padding: 90px 0 72px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Ccircle cx='40' cy='40' r='1.5' fill='rgba(245,166,35,0.12)'/%3E%3C/svg%3E");
        pointer-events: none;
    }

    .contact-hero .hero-inner {
        position: relative;
        z-index: 2;
    }

    .contact-hero h1 {
        font-size: clamp(1.6rem, 4vw, 2.75rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.25;
        margin-bottom: 1.1rem;
        text-shadow: 0 3px 14px rgba(0,0,0,0.55);
    }

    .contact-hero h1 em {
        font-style: normal;
        color: #F5A623;
    }

    .contact-hero .sub-headline {
        font-size: clamp(1rem, 2.4vw, 1.22rem);
        color: rgba(255,255,255,0.88);
        margin-bottom: 1.5rem;
        max-width: 680px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.65;
    }

    .contact-hero .cta-badge {
        display: inline-block;
        background: rgba(245,166,35,0.13);
        border: 1.5px solid rgba(245,166,35,0.4);
        border-radius: 50px;
        padding: 10px 26px;
        color: #F5A623;
        font-weight: 600;
        font-size: 1rem;
    }

    /* ── Contact section wrapper ─────────────────────────────── */
    .contact-section {
        background: #f2f4f7;
        padding: 72px 0 80px;
    }

    /* ── Form card ───────────────────────────────────────────── */
    .contact-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.09);
        padding: 42px 40px;
    }

    .contact-card h5.sub-title {
        color: #F5A623;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 0.8rem;
        margin-bottom: 8px;
    }

    .contact-card h2.heading {
        color: #2d1a00;
        font-weight: 800;
        font-size: clamp(1.35rem, 3vw, 1.85rem);
        margin-bottom: 28px;
        line-height: 1.3;
    }

    /* ── Inputs ──────────────────────────────────────────────── */
    .contact-card .form-control {
        border: 1.5px solid #e2e2e2;
        border-radius: 9px;
        padding: 12px 16px;
        font-size: 0.95rem;
        color: #333;
        transition: border-color .22s, box-shadow .22s;
        background: #fafafa;
        width: 100%;
    }

    .contact-card .form-control:focus {
        border-color: #F5A623;
        box-shadow: 0 0 0 3px rgba(245,166,35,0.15);
        background: #fff;
        outline: none;
    }

    .contact-card .form-control.is-invalid {
        border-color: #dc3545;
    }

    .contact-card textarea.form-control {
        min-height: 118px;
        resize: vertical;
    }

    /* ── Radio / Checkbox pill groups ────────────────────────── */
    .field-group {
        margin-bottom: 24px;
    }

    .field-group-label {
        font-weight: 700;
        color: #2d1a00;
        font-size: 0.92rem;
        display: block;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .option-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .option-pill {
        position: relative;
    }

    .option-pill input[type="radio"],
    .option-pill input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .option-pill label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 18px;
        border: 1.5px solid #e0e0e0;
        border-radius: 50px;
        cursor: pointer;
        font-size: 0.88rem;
        color: #555;
        background: #fafafa;
        transition: border-color .18s, background .18s, color .18s;
        user-select: none;
        line-height: 1.3;
    }

    .option-pill label .pill-dot {
        width: 13px;
        height: 13px;
        border: 2px solid #ccc;
        border-radius: 50%;
        flex-shrink: 0;
        transition: background .18s, border-color .18s;
        display: inline-block;
    }

    .option-pill.is-checkbox label .pill-dot {
        border-radius: 3px;
    }

    .option-pill input:checked + label {
        border-color: #F5A623;
        background: #fff8ee;
        color: #7a3d00;
        font-weight: 600;
    }

    .option-pill input:checked + label .pill-dot {
        background: #F5A623;
        border-color: #F5A623;
    }

    /* ── Validation feedback ─────────────────────────────────── */
    .field-error {
        display: block;
        font-size: 0.82rem;
        color: #dc3545;
        margin-top: 7px;
    }

    /* ── Submit button ───────────────────────────────────────── */
    .btn-demo {
        display: block;
        width: 100%;
        background: linear-gradient(135deg, #F5A623, #d4820e);
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 15px 36px;
        font-size: 1.02rem;
        font-weight: 700;
        letter-spacing: 0.2px;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease;
        box-shadow: 0 4px 18px rgba(245,166,35,0.38);
    }

    .btn-demo:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(245,166,35,0.48);
    }

    .btn-demo:active {
        transform: translateY(0);
    }

    /* ── Contact info sidebar ────────────────────────────────── */
    .contact-info-card {
        background: linear-gradient(160deg, #2d1a00 0%, #5c3400 100%);
        border-radius: 18px;
        padding: 42px 34px;
        color: #fff;
        height: 100%;
    }

    .contact-info-card h2 {
        color: #fff;
        font-weight: 800;
        font-size: 1.7rem;
        margin-bottom: 14px;
        line-height: 1.25;
    }

    .contact-info-card h2 span {
        color: #F5A623;
    }

    .contact-info-card > p {
        color: rgba(255,255,255,0.78);
        font-size: 0.93rem;
        line-height: 1.7;
        margin-bottom: 32px;
    }

    .contact-info-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .contact-info-card ul li {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 28px;
    }

    .contact-info-card .ci-icon {
        width: 48px;
        height: 48px;
        background: rgba(245,166,35,0.13);
        border: 1px solid rgba(245,166,35,0.28);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #F5A623;
        font-size: 1.1rem;
    }

    .contact-info-card .ci-text h5 {
        color: rgba(255,255,255,0.55);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .contact-info-card .ci-text a,
    .contact-info-card .ci-text p {
        color: #fff;
        font-size: 0.98rem;
        font-weight: 600;
        text-decoration: none;
        margin: 0;
    }

    .contact-info-card .ci-text a:hover {
        color: #F5A623;
    }

    /* ── Success alert ───────────────────────────────────────── */
    .success-banner {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        border-radius: 12px;
        padding: 16px 22px;
        margin-bottom: 28px;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* ── Responsive ──────────────────────────────────────────── */
    @media (max-width: 991px) {
        .contact-info-card {
            margin-top: 32px;
            height: auto;
        }
    }

    @media (max-width: 767px) {
        .contact-card {
            padding: 26px 20px;
        }

        .contact-info-card {
            padding: 28px 20px;
        }

        .option-pills {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')

    {{-- ═══════════════════════════════════════════════════════
         HERO BANNER
    ════════════════════════════════════════════════════════ --}}
    <section class="contact-hero">
        <div class="container hero-inner">
            <h1>
                "La inversión en tecnología<br>
                <em>se paga sola.</em><br>
                Déjenos mostrarle cómo."
            </h1>
            <p class="sub-headline">
                Moollish + Sat2Farm le entregan la tecnología.<br class="d-none d-md-block">
                Usted solo pone la tierra y las ganas.
            </p>
            <span class="cta-badge">
                📲 Complete el formulario y un experto le mostrará cómo aplicar esto en su finca.
            </span>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════
         CONTACT SECTION
    ════════════════════════════════════════════════════════ --}}
    <section class="contact-section">
        <div class="container">

            {{-- Success flash --}}
            @if (session('success'))
                <div class="success-banner">
                    <span style="font-size:1.25rem;">✅</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="row align-items-start">

                {{-- ────────────────────────────────────────
                     FORM COLUMN
                ──────────────────────────────────────── --}}
                <div class="col-lg-7" style="margin-bottom:32px;">
                    <div class="contact-card">
                        <h5 class="sub-title">¿Tienes una duda?</h5>
                        <h2 class="heading">Solicita tu demo gratuita</h2>

                        <form action="{{ route('contact.send') }}" method="POST" novalidate>
                            @csrf

                            {{-- Name --}}
                            <div class="form-group" style="margin-bottom:16px;">
                                <input
                                    class="form-control @error('name') is-invalid @enderror"
                                    name="name"
                                    placeholder="Nombre completo *"
                                    type="text"
                                    value="{{ old('name') }}"
                                >
                                @error('name')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email & Phone --}}
                            <div class="row" style="margin-bottom:0;">
                                <div class="col-md-6" style="margin-bottom:16px;">
                                    <div class="form-group">
                                        <input
                                            class="form-control @error('email') is-invalid @enderror"
                                            name="email"
                                            placeholder="Email *"
                                            type="email"
                                            value="{{ old('email') }}"
                                        >
                                        @error('email')
                                            <span class="field-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6" style="margin-bottom:16px;">
                                    <div class="form-group">
                                        <input
                                            class="form-control"
                                            name="phone"
                                            placeholder="Teléfono"
                                            type="text"
                                            value="{{ old('phone') }}"
                                        >
                                    </div>
                                </div>
                            </div>

                            {{-- Field 1: user_type (radio) --}}
                            <div class="field-group">
                                <span class="field-group-label">
                                    ¿Es usted agricultor o pertenece a una empresa agroindustrial? *
                                </span>
                                <div class="option-pills">
                                    @foreach (['Agricultor', 'Empresa agroindustrial'] as $opt)
                                        <div class="option-pill">
                                            <input
                                                type="radio"
                                                name="user_type"
                                                id="ut_{{ $loop->index }}"
                                                value="{{ $opt }}"
                                                {{ old('user_type') === $opt ? 'checked' : '' }}
                                            >
                                            <label for="ut_{{ $loop->index }}">
                                                <span class="pill-dot"></span>
                                                {{ $opt }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('user_type')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Field 2: sat2farm_expectations (checkbox) --}}
                            <div class="field-group">
                                <span class="field-group-label">
                                    ¿Qué espera de la aplicación Sat2Farm? *
                                </span>
                                <div class="option-pills">
                                    @foreach ([
                                        'Análisis de suelos',
                                        'Mejorar el rendimiento',
                                        'Reducir las pérdidas de cosechas',
                                        'Mejor asesoramiento agrícola',
                                    ] as $opt)
                                        <div class="option-pill is-checkbox">
                                            <input
                                                type="checkbox"
                                                name="sat2farm_expectations[]"
                                                id="exp_{{ $loop->index }}"
                                                value="{{ $opt }}"
                                                {{ in_array($opt, old('sat2farm_expectations', [])) ? 'checked' : '' }}
                                            >
                                            <label for="exp_{{ $loop->index }}">
                                                <span class="pill-dot"></span>
                                                {{ $opt }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('sat2farm_expectations')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Field 3: improvement_goals (checkbox) --}}
                            <div class="field-group">
                                <span class="field-group-label">
                                    ¿Qué busca mejorar para sus agricultores? *
                                </span>
                                <div class="option-pills">
                                    @foreach ([
                                        'Mejorar los servicios para los agricultores',
                                        'Añadir capacidades de asesoramiento',
                                        'Integrar la tecnología en mi trabajo',
                                        'Explorando oportunidades',
                                    ] as $opt)
                                        <div class="option-pill is-checkbox">
                                            <input
                                                type="checkbox"
                                                name="improvement_goals[]"
                                                id="goal_{{ $loop->index }}"
                                                value="{{ $opt }}"
                                                {{ in_array($opt, old('improvement_goals', [])) ? 'checked' : '' }}
                                            >
                                            <label for="goal_{{ $loop->index }}">
                                                <span class="pill-dot"></span>
                                                {{ $opt }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('improvement_goals')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Message --}}
                            <div class="form-group" style="margin-bottom:24px;">
                                <textarea
                                    class="form-control @error('message') is-invalid @enderror"
                                    name="message"
                                    placeholder="Coméntanos tus necesidades (opcional)"
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="form-group">
                                <button type="submit" class="btn-demo">
                                    <i class="fa fa-paper-plane" style="margin-right:8px;"></i>
                                    Solicitar demo sin costo
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- ────────────────────────────────────────
                     CONTACT INFO COLUMN
                ──────────────────────────────────────── --}}
                <div class="col-lg-5">
                    <div class="contact-info-card">
                        <h2>Información de <span>contacto</span></h2>
                        <p>
                            Transforma tu finca con tecnología, asegurando productividad y sostenibilidad
                            para el bienestar de tu hato y tu comunidad.
                        </p>
                        <ul>
                            <li>
                                <div class="ci-icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="ci-text">
                                    <h5>Teléfono</h5>
                                    <a href="tel:+573143963815">+57 314 396 3815</a>
                                </div>
                            </li>
                            <li>
                                <div class="ci-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="ci-text">
                                    <h5>Ubicanos</h5>
                                    <p>Barranquilla, Colombia</p>
                                </div>
                            </li>
                            <li>
                                <div class="ci-icon"><i class="fas fa-envelope-open-text"></i></div>
                                <div class="ci-text">
                                    <h5>Correo oficial</h5>
                                    <a href="mailto:info@moollish.com">info@moollish.com</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
