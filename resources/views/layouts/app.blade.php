<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Scripts -->
@stack('scripts')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
    /* I-target ang Nav Bar na ginagamit mo (Hal. ang class na 'navbar') */
    .navbar {
        /* Bawasan ang top at bottom padding */
        padding-top: 0px;   /* Maaaring gawing 5px or 10px */
        padding-bottom: 0px; /* Maaaring gawing 5px or 10px */
    }

    /* Tiyakin na ang elemento na may class na 'navbar-brand' ay hindi magbibigay ng dagdag na space */
    .navbar-brand {
        padding-top: 0;
        padding-bottom: 0;
    }
    .navbar .nav-link {
        color: rgba(0, 0, 0, 0.9); /* O kung anuman ang default na kulay ng text mo */
        transition: color 0.3s ease-in-out; /* Para maging smooth ang transition */
        font-size: 1.00rem; /* Bahagyang mas malaking font (Palitan kung gusto mo) */
        padding-left: 15px !important; /* Dagdagan ang space sa kaliwa */
        padding-right: 15px !important; /* Dagdagan ang space sa kanan */
    }

    /* KRITIKAL: Ang Hover Effect (Maaaring palitan ang #28a745 ng ibang shade ng green) */
    .navbar .nav-link:hover,
    .navbar .nav-link:focus {
        color: #28a745 !important; /* Matingkad na Green color */
        text-decoration: none; /* Tanggalin ang underline kung meron */
    }

    /* Kung ang buttons mo ay may class na 'btn' at 'btn-primary' */
    .btn-primary:hover {
        background-color: #28a745; /* Darker Green background */
        border-color: #28a745;
    }
</style>
</head>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
               <a class="navbar-brand"
   {{-- KRITIKAL: Ang href ay babaguhin depende sa user status --}}
   href="{{
       Auth::check()
           ? (Auth::user()->user_type == 1 /* O 'admin' */
               ? route('admin.dashboard')
               : route('client.dashboard'))
           : route('login')
   }}">

    {{-- Ito ang iyong Logo Image (Ayusin ang height base sa gusto mo) --}}
    <img src="{{ asset('image/aklogo.png') }}"
         alt="{{ config('app.name', 'Laravel') }} Logo"
         style="height: 75px; width: auto;">
</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                         <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                           <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@stack('scripts')
@stack('scripts')
</html>
