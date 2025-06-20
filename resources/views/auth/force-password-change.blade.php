@extends('layouts.app')

{{-- Titre de la page --}}
@section('title', 'Changer votre mot de passe')

{{-- Titre principal du contenu --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155);">Changer votre mot de passe</h1>
@endsection

{{-- Sous-titre du contenu --}}
@section('sousTitreContenu')
    <p class="text-center text-xl text-black">Veuillez définir un nouveau mot de passe pour votre compte.</p>
@endsection

{{-- Contenu principal du formulaire --}}
@section('contenu')
<div class="bg-white p-6 rounded shadow-md max-w-md mx-auto mt-10">
    <p class="text-center text-gray-700 mb-6">
        C'est votre première connexion. Pour des raisons de sécurité, veuillez changer votre mot de passe.
    </p>

    <form method="POST" action="{{ route('password.change') }}">
        @csrf

        <!-- Champ Nouveau mot de passe -->
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Nouveau mot de passe</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror"
                style="color: black;"
            >
            @error('password')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Champ Confirmer le nouveau mot de passe -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmer le nouveau mot de passe</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                style="color: black;"
            >
        </div>

        <div class="flex items-center justify-between">
            <button
                type="submit"
                class="bg-blue-800 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
            >
                Changer le mot de passe
            </button>
        </div>
    </form>
</div>
@endsection
