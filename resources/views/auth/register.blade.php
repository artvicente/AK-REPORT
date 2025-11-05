@extends('layouts.app')

@section('content')
<style>
    /* ------------------------------------- */
    /* KRITIKAL: Custom Green Theme (Same as Login) */
    /* ------------------------------------- */

    /* Green Color Palette */
    :root {
        --green-primary: #1e7a4b; /* Darker Green for buttons */
        --green-light: #28a745;  /* Lighter Green for hover/focus */
    }

    /* Minimal Card Style */
    .card {
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08); /* Minimal shadow */
        border: 1px solid rgba(0, 0, 0, 0.05);
        max-width: 500px; /* Bahagyang mas malapad kaysa Login dahil mas maraming fields */
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

    /* Gawing full-width ang form-group containers (mb-3) */
    .form-label {
        font-weight: 600;
        margin-bottom: 5px;
        display: block; /* Tiyakin na ang label ay nasa itaas ng input */
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        {{-- Inalis ang col-md-8 at gumamit ng col-12 para maging full width sa center --}}
        <div class="col-12">
            <div class="card p-4">
                {{-- Ang title ay inilagay sa loob ng card-body --}}
                <h2 class="text-center mb-4" style="color: var(--green-primary);">{{ __('Create Account') }}</h2>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name Field --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Email Field --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Password Field --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Confirm Password Field --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
