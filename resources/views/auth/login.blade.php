@extends('layouts.app')

{{-- Titre de la page --}}
@section('title', 'Connexion')

{{-- Titre principal du contenu --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155);">Connexion à MY_DGB_TRACKFLOW</h1>
@endsection

{{-- Sous-titre du contenu --}}
@section('sousTitreContenu')
    <p class="text-center text-xl text-black">Connectez-vous pour accéder à votre espace.</p>
@endsection

{{-- Contenu principal du formulaire de connexion --}}
@section('contenu')
    {{-- Ce conteneur va prendre l'espace vertical restant et centrer son contenu (message et formulaire) --}}
    {{-- La hauteur est calculée pour prendre 100% de la hauteur de la vue moins la hauteur du header (140px) et du footer (70px) et le padding vertical de la balise main (2*py-6 = 48px). --}}
    <div class="flex flex-col items-center justify-center w-full min-h-[calc(100vh - 140px - 70px - 48px)]">
        {{-- Le conteneur du formulaire lui-même --}}
        <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
            {{-- Section pour afficher les messages de session (statut) --}}
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Champ Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="email"
                        name="email"
                        value="{{ old('email', request()->query('email')) }}" {{-- NOUVEAU : Pré-remplit avec l'email de l'URL si présent --}}
                        required
                        autofocus
                        autocomplete="username"
                        style="color: black;"
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Champ Mot de passe -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input
                        id="password"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        style="color: black;"
                    >
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
@endsection
