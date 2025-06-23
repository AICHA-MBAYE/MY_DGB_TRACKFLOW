{{-- filepath: resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'Accueil')


@section('contenu')

    @guest

        <div class="flex flex-col items-center justify-center mt-4">
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

                        <button type="submit" class="ml-4 inline-flex items-center px-4 py-2"
                            style="background:#fff; color:#003366; border:2px solid #003366; font-weight:600; border-radius:6px; transition:background 0.2s, color 0.2s;"
                            onmouseover="this.style.background='#003366';this.style.color='#fff';"
                            onmouseout="this.style.background='#fff';this.style.color='#003366';">
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
            <p class="text-lg text-gray-600" style="color:#003366;">
                Vous êtes connecté. Utilisez la barre de navigation ci-dessus pour accéder aux fonctionnalités.
            </p>
            {{-- Vous pouvez ajouter ici d'autres éléments spécifiques au tableau de bord pour les utilisateurs connectés sur la page d'accueil si vous le souhaitez --}}
        </div>
       <div class="flex justify-center my-8">
    <img src="{{ asset('images/welcomedgb.jpeg') }}" alt="DGB Sénégal"
         class="rounded-lg shadow-lg w-full max-w-4xl h-auto"
         style="max-height:300px;">
</div>
    @endauth
@endsection
