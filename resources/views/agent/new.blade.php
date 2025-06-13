@extends('layouts.app')

@section('title', 'Ajouter un agent')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Ajouter un agent</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Remplissez les informations ci-dessous</p>
@endsection


@section('contenu')
<div class="bg-white p-6 rounded shadow-md max-w-3xl mx-auto">
    <form action="{{ route('agent.store') }}" method="POST" novalidate>
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Prénom -->
            <div>
                <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
                <input
                    id="prenom"
                    name="prenom"
                    type="text"
                    required
                    value="{{ old('prenom') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('prenom') border-red-600 @enderror" style="color: black;"                >
                @error('prenom')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom</label>
                <input
                    id="nom"
                    name="nom"
                    type="text"
                    required
                    value="{{ old('nom') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('nom') border-red-600 @enderror"style="color: black;"
                >
                @error('nom')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email (prend toute la largeur) -->
            <div class="md:col-span-2">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('email') border-red-600 @enderror"style="color: black;"
                >
                @error('email')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>
             <!-- Rôle (liste déroulante) -->
             <div class="md:col-span-2">
                <label for="role" class="block text-gray-700 font-semibold mb-2">Rôle</label>
                <select 
                    id="role"
                    name="role"
                    required
                    class="w-full border-gray-300 rounded px-3py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('role') border-red-600 @enderror" style="color: black;">
                    <option value="">-- Sélectionnez un rôle --</option>
                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super-administrateur</option>
                    <option value="admin_sectoriel" {{ old('role') == 'admin_sectoriel' ? 'selected' : '' }}>Administrateur sectoriel</option>
                    <option value="directeur" {{ old('role') == 'directeur' ? 'selected' : '' }}>Directeur</option>
                    <option value="chef_service" {{ old('role') == 'chef_service' ? 'selected' : '' }}>Chef de service</option>
                    <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                </select>
                @error('role')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
             </div>
             <div class="md:col-span-2">
                <label for="direction" class="block text-gray-700 font-semibold mb-2">Direction</label>
                <select
                    id="direction"
                    name="direction"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('direction') border-red-600 @enderror" style="color: black;">
                    <option value="">-- Sélectionnez une direction --</option>
                    <option value="DAP" {{ old('direction') == 'DAP' ? 'selected' : '' }}>Direction de l'Administration et du Personnel (DAP)</option>
                    <option value="DCI" {{ old('direction') == 'DCI' ? 'selected' : '' }}>Direction du Contrôle Interne (DCI)</option>
                    <option value="DSI" {{ old('direction') == 'DSI' ? 'selected' : '' }}>Direction des Systèmes d'Information (DSI)</option>
                    <option value="DPB" {{ old('direction') == 'DPB' ? 'selected' : '' }}>Direction de la Programmation Budgétaire (DPB)</option>
                    <option value="CSS" {{ old('direction') == 'CSS' ? 'selected' : '' }}>Cellule de Suivi et de Synthèse(CSS)</option>
                    <option value="CER" {{ old('direction') == 'CER' ? 'selected' : '' }}>Cellule des Etudes et de la Réglementation(CER)</option>
                </select>
                @error('direction')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 text-right">
            <button
                type="submit"
                class="bg-green-800 text-white px-6 py-2 rounded hover:bg-green-900 transition"
            >
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
