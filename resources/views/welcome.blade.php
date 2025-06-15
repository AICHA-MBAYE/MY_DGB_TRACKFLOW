@extends('layouts.app')

@section('title', 'Accueil')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Accueil</h1>
@endsection

@section('contenu')
    <div class="text-center py-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-4" style="color: black;">Bienvenue dans MY_DGB_TRACKFLOW</h1>
        <p class="text-lg text-gray-600 mb-8" style="color: black;">
            Plateforme de gestion et de suivi des agents de la Direction Générale du Budget.
        </p>
    </div>

    {{-- Affiche le formulaire de connexion si l'utilisateur n'est PAS connecté --}}
    @guest
        <div class="flex flex-col items-center justify-center min-h-screen -mt-20"> {{-- Ajustez le -mt-20 si besoin --}}
            <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-center mb-6" style="color: rgb(34, 34, 155);">Connexion</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Champ Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" style="color: black;">
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="password" name="password" required autocomplete="current-password" style="color: black;">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Case à cocher Se souvenir de moi -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                Mot de passe oublié ?
                            </a>
                        @endif

                        <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-900 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endguest

    {{-- Le contenu principal de la page pour les utilisateurs connectés pourrait aller ici si vous voulez --}}
    @auth
        <div class="text-center py-10">
            <p class="text-lg text-gray-600" style="color: rgb(64, 113, 4);">
                Vous êtes connecté. Utilisez la barre de navigation ci-dessus pour accéder aux fonctionnalités.
            </p>
            {{-- Vous pouvez ajouter ici d'autres éléments spécifiques au tableau de bord pour les utilisateurs connectés sur la page d'accueil si vous le souhaitez --}}
        </div>
    @endauth
@endsection
