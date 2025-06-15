@extends('layouts.app')

{{-- Le titre de la page affiché dans le navigateur --}}
@section('title', 'Inscription Agent')

{{-- Titre principal du contenu affiché sur la page --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Inscription d'un nouvel agent</h1>
@endsection

{{-- Sous-titre du contenu --}}
@section('sousTitreContenu')
    <p class="text-center text-xl text-black">Remplissez le formulaire d'inscription ci-dessous</p>
@endsection


{{-- Contenu principal du formulaire d'inscription --}}
@section('contenu')
<div class="bg-white p-6 rounded shadow-md max-w-3xl mx-auto">
    {{-- Formulaire d'inscription --}}
    {{-- L'action du formulaire pointe vers la route 'agent.store' pour gérer l'enregistrement --}}
    <form action="{{ route('agent.store') }}" method="POST" novalidate>
        @csrf {{-- Protection CSRF obligatoire pour les formulaires Laravel --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Champ Prénom -->
            <div>
                <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
                <input
                    id="prenom"
                    name="prenom"
                    type="text"
                    required
                    value="{{ old('prenom') }}" {{-- old() maintient la valeur en cas d'erreur de validation --}}
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('prenom') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('prenom') {{-- Affiche les erreurs de validation pour le champ 'prenom' --}}
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
                    value="{{ old('nom') }}"
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
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('email') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('email')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Liste déroulante Rôle -->
            <div class="md:col-span-2">
                <label for="role" class="block text-gray-700 font-semibold mb-2">Rôle</label>
                <select
                    id="role"
                    name="role"
                    required
                    class="w-full border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('role') border-red-600 @enderror"
                    style="color: black;">
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

            <!-- Liste déroulante Direction -->
            <div class="md:col-span-2">
                <label for="direction" class="block text-gray-700 font-semibold mb-2">Direction</label>
                <select
                    id="direction"
                    name="direction"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('direction') border-red-600 @enderror"
                    style="color: black;">
                    <option value="">-- Sélectionnez une direction --</option>
                    {{--
                        Note: Si vos directions sont stockées en base de données, vous devriez itérer
                        sur une collection de directions passée au lieu d'écrire en dur.
                        Ex: @foreach($directions as $direction) <option value="{{ $direction->code }}">...</option> @endforeach
                        Assurez-vous que la valeur (value) envoyée au serveur correspond à ce que votre validation attend (code ou ID).
                    --}}
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

        {{-- Bouton d'inscription --}}
        <div class="mt-6 text-right">
            <button
                type="submit"
                class="bg-blue-800 text-white px-6 py-2 rounded hover:bg-blue-900 transition" {{-- Couleur du bouton changée pour "Inscription" --}}
            >
                S'inscrire
            </button>
        </div>
    </form>
</div>
@endsection
