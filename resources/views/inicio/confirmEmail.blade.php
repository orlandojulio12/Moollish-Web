@extends('layouts')

@section('title')
    Moollish - Correo confirmado
@endsection

@section('styles')
<style>
    .confirmation-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 20px;
        text-align: center;
        border-radius: 10px;
        background: white;
        border: 1px solid #eaebef;
    }

    .confirmation-icon {
        margin-bottom: 20px;
    }

    .confirmation-icon svg {
        width: 90px;
        height: 90px;
    }

    .confirmation-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #333;
    }

    .confirmation-message {
        font-size: 16px;
        line-height: 1.5;
        max-width: 500px;
        margin-bottom: 30px;
        color: #666;
    }

    .btn-home {
        background-color: #E49B39;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-home:hover {
        background-color: #B57827;
        color: white;
    }

    .success-checkmark {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
    }

    .check-icon {
        width: 80px;
        height: 80px;
        position: relative;
        border-radius: 50%;
        box-sizing: content-box;
        border: 4px solid #4CAF50;
    }

    .check-icon::before {
        top: 43px;
        left: 19px;
        transform: rotate(45deg);
        position: absolute;
        content: '';
        width: 15px;
        height: 4px;
        background-color: #4CAF50;
    }

    .check-icon::after {
        top: 38px;
        left: 26px;
        transform: rotate(135deg);
        position: absolute;
        content: '';
        width: 35px;
        height: 4px;
        background-color: #4CAF50;
    }
</style>
@endsection

@section('content')
<div class="confirmation-container">
    <div class="success-checkmark">
        <div class="check-icon"></div>
    </div>

    <h1 class="confirmation-title">¡Correo electrónico verificado!</h1>

    <p class="confirmation-message">
        Has confirmado exitosamente tu correo electrónico. Ahora puedes disfrutar de todas las funcionalidades de Moollish sin limitaciones.
    </p>

    <a href="{{ route('inicio') }}" class="btn-home">
        Volver al inicio
    </a>
</div>
@endsection

@section('scripts')
<script>
    // Animación sencilla para mejorar la experiencia del usuario
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.confirmation-container');
        container.style.opacity = '0';
        container.style.transform = 'translateY(20px)';
        container.style.transition = 'opacity 0.8s ease, transform 0.8s ease';

        setTimeout(() => {
            container.style.opacity = '1';
            container.style.transform = 'translateY(0)';
        }, 200);
    });
</script>
@endsection
