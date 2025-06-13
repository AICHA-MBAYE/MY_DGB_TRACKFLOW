<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'MY_DGB_TRACKFLOW')</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Importation de Tailwind CSS (via Vite pour Laravel) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Styles généraux pour le corps de la page */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f4f6f9;
            /* Retire le display: flex ici car les éléments fixes vont s'en occuper */
            min-height: 100vh; /* La hauteur minimale de la page est de 100% de la hauteur de la vue */
            margin: 0;
            padding-top: 140px; /* Espace pour le header fixe (somme de header-logo-text et nav-buttons) */
            padding-bottom: 70px; /* Espace pour le footer fixe */
            box-sizing: border-box; /* S'assure que padding n'ajoute pas à la taille totale */
        }

        /* Styles pour l'en-tête (logo et texte) */
        .header-logo-text {
            display: flex; /* Utilise Flexbox pour aligner le logo et le texte */
            align-items: center; /* Centre les éléments verticalement */
            justify-content: center; /* Centre les éléments horizontalement */
            gap: 20px; /* Ajoute un espace entre le logo et le texte */
            padding: 20px; /* Padding autour de l'en-tête */
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            flex-wrap: wrap; /* Permet aux éléments de passer à la ligne sur petits écrans */
            position: fixed; /* Rend l'en-tête fixe */
            top: 0; /* Ancre l'en-tête en haut de la page */
            left: 0;
            width: 100%;
            z-index: 1000; /* Assure que l'en-tête est au-dessus des autres contenus */
        }

        .header-logo-text img {
            height: 80px; /* Taille du logo ajustée */
        }

        .header-logo-text span {
            color: #003366; /* midnight blue */
            font-size: 2.5rem; /* Taille du texte ajustée, sera responsive */
            font-weight: 800; /* Gras */
            white-space: nowrap; /* Empêche le texte de se couper en plusieurs lignes */
        }

        /* Responsive adjustments for header text */
        @media (max-width: 768px) {
            .header-logo-text {
                flex-direction: column; /* Empile le logo et le texte sur les petits écrans */
                gap: 10px;
            }
            .header-logo-text span {
                font-size: 2rem; /* Taille du texte plus petite sur mobile */
                text-align: center;
                white-space: normal; /* Permet le retour à la ligne sur mobile si nécessaire */
            }
        }

        /* Styles de la navigation */
        .nav-buttons {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            /* La navigation ne sera plus directement sous l'en-tête fixe, mais fera partie du contenu défilant */
            /* Nous allons ajuster le padding-top du body pour compenser la hauteur de l'en-tête + nav */
        }

        nav a {
            color:#122f77;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #216f05;
        }

        .nav-right-buttons a {
            background-color: #001e64; /* dark blue */
            color: white;
            margin-left: 10px; /* Espace entre les boutons */
            text-decoration: none;
        }

        .nav-right-buttons a:hover {
            background-color: #216f05; /* Darker blue on hover */
        }

        /* Styles du pied de page */
        footer {
            background-color: #003366; /* midnight blue */
            color: white;
            padding: 1rem;
            text-align: center;
            position: fixed; /* Rend le footer fixe */
            bottom: 0; /* Ancre le footer en bas de la page */
            left: 0;
            width: 100%;
            z-index: 1000; /* Assure que le footer est au-dessus des autres contenus */
        }

        /* Styles existants conservés pour les titres, boutons, tables, etc. */
        h1 {
            color: #003366;
        }

        /* Styles pour les titres de contenu */
        .content-header h1 {
            font-size: 2rem; /* Taille plus grande pour le titre principal */
            margin-bottom: 0.5rem; /* Espace en dessous du titre principal */
        }

        .content-header p {
            font-size: 1rem; /* Taille plus grande pour le sous-titre */
            margin-bottom: 2rem; /* Plus d'espace en dessous du sous-titre et avant le formulaire */
            color: #4a5568; /* Couleur légèrement plus foncée pour une meilleure lisibilité */
        }

        /* Styles généraux pour tous les boutons natifs */
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
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Le conteneur principal de la page n'est plus nécessaire car header et footer sont fixes -->
    <!-- Le header et la nav sont maintenant combinés dans un en-tête fixe -->
    <div class="fixed-header-container">
        <header class="header-logo-text">
            <img src="{{ asset('images/dgb-logo.png') }}" alt="Logo DGB" />
            <span>MY_DGB_TRACKFLOW</span>
        </header>

        <nav class="nav-buttons">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div>
                    <a href="{{ route('dashboard') }}" class="dashboard-link">Dashboard</a>
                </div>

                <div class="flex items-center nav-right-buttons">
                    <a href="{{ route('agents.create') }}" class="btn btn-primary">Ajouter un agent</a>
                    <a href="{{ route('agents.index') }}" class="btn btn-primary">Liste des agents</a>
                    <a href="{{ route('demande_absence.create') }}" class="btn btn-primary">Demande d'absence</a>
                    <a href="{{ route('demande_absence.index') }}" class="btn btn-primary">Liste des absences</a>
                </div>
            </div>
        </nav>
    </div>

    <!-- En-tête de contenu (si présent) - Ajout d'une classe pour les styles spécifiques -->
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

    <!-- Contenu principal de la page. flex-grow: 1 assure qu'il prend l'espace restant -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8" style="flex-grow: 1;">
        @yield('contenu')
    </main>

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
            background-color: #006400; /* vert */
            border: none;
        }
        .btn-primary:hover {
            background-color: #006400;
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
