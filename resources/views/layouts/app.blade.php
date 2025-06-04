<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'MY_DGB_TRACKFLOW')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    body {
        font-family: 'Figtree', sans-serif;
        background-color: #f4f6f9;
    }

    nav {
        background-color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .logo-text {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo-text img {
        height: 50px;
    }

    .logo-text span {
        color: #003366; /* midnight blue */
        font-size: 1.5rem;
        font-weight: 700;
    }

    footer {
        background-color: #003366; /* midnight blue */
        color: white;
        padding: 1rem;
        text-align: center;
        margin-top: 2rem;
    }

    h1 {
        color: #003366;
    }

    /* Styles généraux pour tous les boutons natifs, au cas où classes non ajoutées */
    button, input[type="button"], input[type="submit"] {
        background-color: #006400; /* dark green */
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover, input[type="button"]:hover, input[type="submit"]:hover {
        opacity: 0.9;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-primary {
        background-color: #006400; /* dark green */
        color: white;
        border: none;
    }

    .btn-warning {
        background-color: #ffcc00;
        color: black;
    }

    .btn-danger {
        background-color: #cc0000;
        color: white;
    }

    .btn:hover {
        opacity: 0.9;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background-color: white;
        border-radius: 0.375rem;
        overflow: hidden;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    th {
        background-color: #003366;
        color: white;
    }

    tr:hover {
        background-color: #f1f5f9;
    }

    .float-right {
        float: right;
        margin-top: 1rem;
    }
    .titre-contenu {
        font-size: 3rem; /* très grand */
        font-weight: 800;
    }

    .sous-titre-contenu {
        font-size: 1.75rem;
        color: #4B5563; /* gris foncé */
    }
</style>

</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <div class="logo-text">
                    <img src="{{ asset('images/dgb-logo.png') }}" alt="Logo DGB" />
                    <span class="text-xl font-bold text-gray-800">MY_DGB_TRACKFLOW</span>
                </div>
                @include('layouts.navigation')
            </div>
        </nav>

        <!-- Page Heading -->
        @hasSection('titreContenu')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900">@yield('titreContenu')</h1>
                    @hasSection('sousTitreContenu')
                        <p class="text-gray-600">@yield('sousTitreContenu')</p>
                    @endif
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @yield('contenu')
        </main>

        <!-- Footer -->
        <footer>
            DGB-SENEGAL 2025 - Tous droits réservés
        </footer>

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
            background-color: #28a745; /* vert */
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
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
