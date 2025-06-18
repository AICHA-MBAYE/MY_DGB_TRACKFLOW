@extends('layouts.app')

@section('title', 'Changer le mot de passe')

@section('contenu')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Changer votre mot de passe</h2>
    <form method="POST" action="{{ route('password.change') }}">
        @csrf
        <div class="mb-4">
            <label for="password" class="block mb-1">Nouveau mot de passe</label>
            <input type="password" name="password" id="password" required class="w-full border px-3 py-2 rounded">
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border px-3 py-2 rounded">
        </div>
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded">Changer</button>
    </form>
</div>
@endsection