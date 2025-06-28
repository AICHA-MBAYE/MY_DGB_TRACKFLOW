<!-- filepath: resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'MY_DGB_TRACKFLOW')</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Scripts Tailwind CSS (assurez-vous que c'est bien configuré ou incluez le CDN si nécessaire) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f4f6f9;
            min-height: 100vh;
            margin: 0;
            padding-top: 90px;
            padding-bottom: 70px;
            box-sizing: border-box;
        }
        .main-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 1000;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
        }
        .main-navbar .logo img {
            height: 55px;
        }
        .main-navbar .nav-right-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .main-navbar .nav-right-buttons a,
        .main-navbar .nav-right-buttons button {
            background-color: #fff;
            color: #003366 !important;   /* texte bleu foncé */
    border: 1px solid #003366;
            border-radius: 6px;
            padding: 7px 18px;
            font-weight: 500;
            font-size: 1rem;
            transition: background 0.2s;
            text-decoration: none;
        }
        .main-navbar .nav-right-buttons a:hover,
        .main-navbar .nav-right-buttons button:hover {
            background-color: #00509e;
            color: #fff !important;
        }
        .main-navbar .nav-right-buttons form {
            margin: 0;
        }
        /* Formulaires modernes et compacts */
        form.stylish-form {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 2rem 2.5rem;
            max-width: 400px;
            margin: 2rem auto;
        }
        form.stylish-form label {
            font-weight: 600;
            color: #003366;
            margin-bottom: 0.3rem;
        }
        form.stylish-form input,
        form.stylish-form select,
        form.stylish-form textarea {
            border-radius: 7px !important;
            border: 1px solid #d1d5db;
            padding: 0.5rem 0.9rem;
            font-size: 1rem;
            margin-bottom: 1rem;
            background: #f8fafc;
            transition: border 0.2s;
        }
        form.stylish-form input:focus,
        form.stylish-form select:focus,
        form.stylish-form textarea:focus {
            border: 1.5px solid #003366;
            outline: none;
            background: #fff;
        }
        form.stylish-form button[type="submit"] {
            width: 100%;
            margin-top: 0.5rem;
            background: #003366;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.6rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background 0.2s;
        }
        form.stylish-form button[type="submit"]:hover {
            background: #00509e;
        }
        /* Responsive */
        @media (max-width: 600px) {
            .main-navbar {
                flex-direction: column;
                height: auto;
                padding: 10px 10px;
                gap: 10px;
            }
            .main-navbar .logo img {
                height: 45px;
            }
            form.stylish-form {
                padding: 1.2rem 0.7rem;
            }
        }
        footer {
            background-color: #003366;
            color: white;
            padding: 1rem;
            text-align: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
    </style>
</head>
<body class="font-sans antialiased">
    @php use Illuminate\Support\Facades\Auth; @endphp
    @php use Illuminate\Support\Facades\Route; @endphp

    <nav class="main-navbar">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/dgb-logo.png') }}" alt="Logo DGB">
            </a>
        </div>
        <div class="nav-right-buttons">
    @auth
        @if (!Route::is('password.force_change'))
            @if (Auth::user()->role === 'agent')
                <a href="{{ route('demande_absence.create') }}"
                   @if(Route::is('demande_absence.create')) style="background:#00509e; color:#fff !important;" @endif>
                   Demande d'absence
                </a>
                <a href="{{ route('demande_absence.index') }}"
                   @if(Route::is('demande_absence.index')) style="background:#00509e; color:#fff !important;" @endif>
                   Liste des absences
                </a>
            @endif
            @if (Auth::user()->role === 'chef_service')
                <a href="{{ route('agent.validated_index') }}"
                   @if(Route::is('agent.validated_index')) style="background:#00509e; color:#fff !important;" @endif>
                   Agents Validés
                </a>
                <a href="{{ route('chef.validation') }}"
                   @if(Route::is('chef.validation')) style="background:#00509e; color:#fff !important;" @endif>
                   Validation
                </a>
                <a href="{{ route('chef.agents') }}"
                   @if(Route::is('chef.agents')) style="background:#00509e; color:#fff !important;" @endif>
                   Statistiques
                </a>
                <a href="{{ route('chef.historique') }}"
                   @if(Route::is('chef.historique')) style="background:#00509e; color:#fff !important;" @endif>
                   Historique des Validations
                </a>
            @endif
            @if (Auth::user()->role === 'directeur')
                <a href="{{ route('agent.validated_index') }}"
                   @if(Route::is('agent.validated_index')) style="background:#00509e; color:#fff !important;" @endif>
                   Agents Validés
                </a>
                <a href="{{ route('directeur.validation') }}"
                   @if(Route::is('directeur.validation')) style="background:#00509e; color:#fff !important;" @endif>
                   Validation
                </a>
                <a href="{{ route('directeur.historique') }}"
                   @if(Route::is('directeur.historique')) style="background:#00509e; color:#fff !important;" @endif>
                   Historique des Validations
                </a>
            @endif
            @if (in_array(Auth::user()->role, ['admin_sectoriel', 'super_admin']))
                <a href="{{ route('agent.index') }}"
                   @if(Route::is('agent.index')) style="background:#00509e; color:#fff !important;" @endif>
                   Gestion des Agents
                </a>
                <a href="{{ route('agent.validated_index') }}"
                   @if(Route::is('agent.validated_index')) style="background:#00509e; color:#fff !important;" @endif>
                   Agents Validés
                </a>
            @endif
        @endif
    @endauth
</div>

        <div class="nav-right-buttons">
            @guest
                @if (!Route::is('login'))
                    <a href="{{ route('agent.register') }}">Inscription</a>
                @endif
            @endguest

            @auth
                @if (!Route::is('password.force_change'))
                    {{-- ...vos liens de navigation selon le rôle... --}}
                @endif

                {{-- Remplacement du bouton Déconnexion par un menu Profil --}}
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2" style="font-size: 1.5rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownProfile">
                        <li class="px-3 py-2">
                            <div class="fw-bold">Agent: {{ Auth::user()->prenom ?? Auth::user()->name }} {{ Auth::user()->nom ?? '' }}</div>
                            <div class="text-muted small">Rôle: {{ Auth::user()->role ?? '-' }}</div>
                            <div class="text-muted small">Email: {{ Auth::user()->email }}</div>
                            <div class="text-muted small">Direction : {{ Auth::user()->direction ?? '-' }}</div>
                            <div class="text-muted small">Division : {{ Auth::user()->division ?? '-' }}</div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
    <form method="POST" action="{{ route('logout') }}" id="logout-form">
        @csrf
        <button type="submit" class="dropdown-item text-danger"
            onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');">
            <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
        </button>
    </form>
</li>
                    </ul>
                </div>
            @endauth
        </div>
    </nav>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-auto max-w-7xl mt-4 rounded" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-auto max-w-7xl mt-4 rounded" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mx-auto max-w-7xl rounded" role="alert">
            {{ session('warning') }}
        </div>
    @endif
    @if (session('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mx-auto max-w-7xl mt-4 rounded" role="alert">
            {{ session('info') }}
        </div>
    @endif

    @hasSection('titreContenu')
        <header class="bg-white shadow content-header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="font-bold text-gray-900">@yield('titreContenu')</h1>
                @hasSection('sousTitreContenu')
                    <p class="text-gray-600">@yield('sousTitreContenu')</p>
                @endif
            </div>
        </header>
    @endif

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" style="flex-grow: 1;">
        @yield('contenu')
    </main>

    <footer>
        DGB-SENEGAL 2025 - Tous droits réservés
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande d'absence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            color: #0d0c0c;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .logo-container {
            text-align: center;
            padding: 20px 0;
            background-color: #ffffff;
        }
        .logo-container img {
            height: 100px;
        }
        .content-wrapper {
            background-color: #123a3c;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-top: 30px;
        }
        .btn-primary {
            background-color: #0a0064; /* vert */
            border: none;
        }
        .btn-primary:hover {
            background-color: #003464;
        }
        label {
            font-weight: bold;
        }
        input, select, textarea {
            border-radius: 5px !important;
        }
    </style>
</head>
<body>


</body>
</html>
