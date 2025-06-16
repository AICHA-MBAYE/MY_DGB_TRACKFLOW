@extends('layouts.app')

{{-- Le titre de la page affiché dans le navigateur --}}
@section('title', 'Modifier Inscription Rejetée')

{{-- Titre principal du contenu affiché sur la page --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Modifier votre inscription</h1>
@endsection

{{-- Sous-titre du contenu --}}
@section('sousTitreContenu')
    <p class="text-center text-xl text-black">Veuillez corriger les informations de votre inscription ci-dessous et soumettre à nouveau.</p>
@endsection


{{-- Contenu principal du formulaire de modification --}}
@section('contenu')
<div class="bg-white p-6 rounded shadow-md max-w-3xl mx-auto">
    {{-- Formulaire de modification --}}
    {{-- L'action du formulaire pointe vers la route 'agent.update_rejected_registration' --}}
    <form action="{{ route('agent.update_rejected_registration', $agent->id) }}" method="POST" novalidate>
        @csrf {{-- Protection CSRF obligatoire --}}
        {{-- Laravel ne reconnaît pas PUT/PATCH via HTML form par défaut, donc utilisez @method --}}
        @method('POST') {{-- Nous utilisons POST pour simuler une PUT/PATCH car la route est POST --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Champ Prénom -->
            <div>
                <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
                <input
                    id="prenom"
                    name="prenom"
                    type="text"
                    required
                    value="{{ old('prenom', $agent->prenom) }}" {{-- old() maintient la valeur, sinon utilise celle de l'agent --}}
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('prenom') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('prenom')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Champ Nom -->
            <div>
                <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom</label>
                <input
                    id="nom"
                    name="nom"
                    type="text"
                    required
                    value="{{ old('nom', $agent->nom) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('nom') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('nom')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Champ Email (prend toute la largeur sur les écrans moyens) -->
            <div class="md:col-span-2">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                    value="{{ old('email', $agent->email) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('email') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('email')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Liste déroulante Rôle (peut être modifiée par l'agent ou restreinte par l'admin) -->
            <div class="md:col-span-2">
                <label for="role" class="block text-gray-700 font-semibold mb-2">Rôle</label>
                <select
                    id="role"
                    name="role"
                    required
                    class="w-full border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('role') border-red-600 @enderror"
                    style="color: black;">
                    <option value="">-- Sélectionnez un rôle --</option>
                    {{-- Les rôles que l'agent peut choisir lors de la ré-inscription --}}
                    <option value="super_admin" {{ old('role', $agent->role) == 'super_admin' ? 'selected' : '' }}>Super-administrateur</option>
                    <option value="admin_sectoriel" {{ old('role', $agent->role) == 'admin_sectoriel' ? 'selected' : '' }}>Administrateur sectoriel</option>
                    <option value="directeur" {{ old('role', $agent->role) == 'directeur' ? 'selected' : '' }}>Directeur</option>
                    <option value="chef_service" {{ old('role', $agent->role) == 'chef_service' ? 'selected' : '' }}>Chef de service</option>
                    <option value="agent" {{ old('role', $agent->role) == 'agent' ? 'selected' : '' }}>Agent</option>
                </select>
                @error('role')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Liste déroulante Direction (peut être modifiée par l'agent) -->
            <div class="md:col-span-2">
                <label for="direction" class="block text-gray-700 font-semibold mb-2">Direction</label>
                <select
                    id="direction"
                    name="direction"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('direction') border-red-600 @enderror"
                    style="color: black;">
                    <option value="">-- Sélectionnez une direction --</option>
                    <option value="DAP" {{ old('direction', $agent->direction) == 'DAP' ? 'selected' : '' }}>Direction de l'Administration et du Personnel (DAP)</option>
                    <option value="DCI" {{ old('direction', $agent->direction) == 'DCI' ? 'selected' : '' }}>Direction du Contrôle Interne (DCI)</option>
                    <option value="DSI" {{ old('direction', $agent->direction) == 'DSI' ? 'selected' : '' }}>Direction des Systèmes d'Information (DSI)</option>
                    <option value="DPB" {{ old('direction', $agent->direction) == 'DPB' ? 'selected' : '' }}>Direction de la Programmation Budgétaire (DPB)</option>
                    <option value="CSS" {{ old('direction', $agent->direction) == 'CSS' ? 'selected' : '' }}>Cellule de Suivi et de Synthèse(CSS)</option>
                    <option value="CER" {{ old('direction', $agent->direction) == 'CER' ? 'selected' : '' }}>Cellule des Etudes et de la Réglementation(CER)</option>
                </select>
                @error('direction')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Bouton de soumission --}}
        <div class="mt-6 text-right">
            <button
                type="submit"
                class="bg-blue-800 text-white px-6 py-2 rounded hover:bg-blue-900 transition"
            >
                Soumettre les modifications
            </button>
        </div>
    </form>
</div>
@endsection