@extends('layouts.admin.simple')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="text-center mb-4">
                <div class="brand-logo mb-3">
                    <i class="fas fa-newspaper fa-3x text-primary"></i>
                </div>
                <h1 class="h3 text-gray-900 mb-0">Şifre Sıfırlama</h1>
                <p class="text-muted">E-posta adresinizi girin</p>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="/admin/forgot-password">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">E-posta Adresi</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Sıfırlama Bağlantısı Gönder
                            </button>

                            <a href="/admin/login" class="btn btn-link text-center">
                                <i class="fas fa-arrow-left me-2"></i>Giriş Sayfasına Dön
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.brand-logo {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(66, 133, 244, 0.1);
    border-radius: 50%;
}

.brand-logo i {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.card {
    border: none;
    border-radius: 0.5rem;
}

.input-group-text {
    background-color: transparent;
    border-right: none;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-color: #dee2e6;
    box-shadow: none;
}

.input-group:focus-within {
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
    border-radius: 0.375rem;
}

.input-group:focus-within .input-group-text,
.input-group:focus-within .form-control {
    border-color: #86b7fe;
}

.btn-primary {
    padding: 0.5rem 1rem;
    font-weight: 500;
}

body {
    background-color: #f8f9fa;
}
</style>
@endsection
