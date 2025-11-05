@extends('layouts.app')

@section('content')
<style>
    :root {
        --green-primary: #1e7a4b; /* Darker Green for buttons */
        --green-light: #28a745;  /* Lighter Green for hover/focus */
        --green-bg: #e6ffe6;     /* Very Light Green background (optional) */
    }

    /* Minimal Card Style */
    .card {
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); /* Minimal shadow */
        border: 1px solid rgba(0, 0, 0, 0.05);
        max-width: 450px; /* Mas pinakipot para minimal */
        margin: 50px auto; /* I-center at lagyan ng space sa taas/baba */
    }

    /* Alisin ang card header space at gawing minimal ang title */
    .card-header {
        display: none; /* Tanggalin ang default header */
    }

    /* Custom Styling para sa Green Button */
    .btn-primary {
        background-color: var(--green-primary);
        border-color: var(--green-primary);
        transition: all 0.3s ease;
        padding: 10px 20px;
        border-radius: 8px; /* Rounded corners */
        font-weight: 600;
        width: 100%; /* Gawing full-width ang button */
    }

    .btn-primary:hover, .btn-primary:focus {
        background-color: var(--green-light);
        border-color: var(--green-light);
        box-shadow: 0 4px 8px rgba(30, 122, 75, 0.4) !important;
    }

    /* Minimal Form Controls/Input Focus */
    .form-control:focus {
        border-color: var(--green-light);
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25); /* Green glow effect */
    }

    /* Forgot Password link (gawing green din) */
    .btn-link {
        color: var(--green-primary);
    }
    .btn-link:hover {
        color: var(--green-light);
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card p-4">
                <h2 class="text-center mb-4" style="color: var(--green-primary);">{{ __('Welcome Back') }}</h2>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
