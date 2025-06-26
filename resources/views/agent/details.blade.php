@extends('layouts.app')

{{-- Titre de la page --}}
@section('title', 'Informations de l\'agent')

{{-- Titre principal du contenu --}}
@section('titreContenu')
    <h1 class="text-center text-4xl font-extrabold tracking-tight mb-2" style="color: #22229b; font-family: 'Dancing Script', cursive; letter-spacing: 1px;">
        <span class="inline-block align-middle mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </span>
        Détails de l'agent :
    </h1>
@endsection

{{-- Le sous-titre est supprimé comme demandé --}}

{{-- Contenu principal de la page --}}
@section('contenu')
@php use Illuminate\Support\Facades\Auth; @endphp

{{-- Import de la police "Dancing Script" pour un effet calligraphique --}}
{{-- Idéalement, ce lien devrait être dans layouts/app.blade.php --}}
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">

<div class="flex justify-center items-center min-h-screen bg-gray-100 py-4 px-2">
    {{-- La largeur maximale est augmentée de 4cm (2cm supplémentaires) --}}
    <div class="bg-white p-6 rounded-lg shadow-xl w-full" style="max-width: calc(24rem + 4cm); min-width: 350px;" mx-auto border border-gray-200>
        
        {{-- Nom et Prénom en calligraphie --}}
        <h2 class="text-center text-4xl font-bold text-gray-800 mb-6" style="font-family: 'Dancing Script', cursive; line-height: 1.2;">
            {{ $agent->prenom }} <br class="sm:hidden"/> {{ $agent->nom }}
        </h2>

        {{-- Section informations sous forme de formulaire --}}
        <div class="space-y-3 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Email:</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200 break-words">{{ $agent->email }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Direction:</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200">{{ ucfirst(str_replace('_', ' ', $agent->direction)) }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Division:</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200">{{ $agent->division ? ucfirst(str_replace('_', ' ', $agent->division)) : 'N/A' }}</p>
            </div>
            @if($agent->created_at) {{-- Afficher la date et l'heure d'inscription si disponible --}}
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Date et heure d'inscription:</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200">{{ $agent->created_at->format('d/m/Y H:i') }}</p>
            </div>
            @endif
        </div>

        {{-- Affichage des erreurs de validation --}}
        @if ($errors->any())
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Erreur(s) :</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Boutons de validation/rejet --}}
        @if ($agent->status === 'pending' && in_array(Auth::user()->role, ['super_admin', 'admin_sectoriel']))
            <form action="{{ route('agent.validateAndAssignPassword', $agent) }}" method="POST" class="mb-6">
                @csrf
                <div class="mb-4">
                    <label for="role-select-{{ $agent->id }}" class="block text-gray-700 text-sm font-semibold mb-2">Rôle à attribuer:</label>
                    <select id="role-select-{{ $agent->id }}" name="role_to_assign" class="border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-blue-500 focus:border-blue-500 text-base">
                        <option value="">-- Choisir un rôle --</option>
                        @if(Auth::user()->role === 'super_admin')
                            <option value="super_admin">Super administrateur</option>
                            <option value="admin_sectoriel">Administrateur sectoriel</option>
                        @endif
                        <option value="directeur">Directeur</option>
                        <option value="chef_service">Chef de service</option>
                        <option value="agent">Agent</option>
                    </select>
                </div>

                <div class="flex justify-center items-center mt-4 space-x-16">
                    <button type="submit" class="bg-gradient-to-br from-green-700 to-green-900 hover:from-green-800 hover:to-green-950 text-white font-bold p-3 rounded-full shadow-xl transition duration-200 ease-in-out flex items-center justify-center text-3xl border-4 border-white hover:border-green-300 focus:ring-4 focus:ring-green-200 outline-none" style="width:56px; height:56px;" title="Valider et Attribuer un MDP">
                        <i class="fa-solid fa-circle-check"></i>
                    </button>
                    <button type="button" onclick="if(confirm('Voulez-vous vraiment rejeter cet agent ?')) { document.getElementById('reject-form-{{ $agent->id }}').submit(); }" class="bg-gradient-to-br from-red-800 to-red-900 hover:from-red-900 hover:to-red-950 text-white font-bold p-3 rounded-full shadow-xl transition duration-200 ease-in-out flex items-center justify-center text-3xl border-4 border-white hover:border-red-300 focus:ring-4 focus:ring-red-200 outline-none" style="width:56px; height:56px;" title="Rejeter">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
            </form>
            <form id="reject-form-{{ $agent->id }}" action="{{ route('agent.reject', $agent) }}" method="POST" class="hidden">
                @csrf
                @method('PUT')
            </form>
        @else
            {{-- Message si les actions ne sont pas disponibles --}}
            <div class="mt-4 text-center">
                <p class="text-gray-500 text-sm mt-6 text-center italic">Les actions pour cet agent sont complétées ici.</p>
            </div>
        @endif

        <div class="mt-6 pt-3 border-t border-gray-200 text-center">
            <a href="{{ route('agent.index') }}" class="inline-flex items-center justify-center bg-gradient-to-br from-[#191970] to-blue-900 hover:from-blue-900 hover:to-blue-950 text-white font-bold p-3 rounded-full shadow-xl transition duration-200 ease-in-out text-3xl border-4 border-white hover:border-blue-300 focus:ring-4 focus:ring-blue-200 outline-none" style="width:56px; height:56px;" title="Retour">
                <i class="fa-solid fa-circle-arrow-left"></i>
            </a>
        </div>
    </div>
</div>
@endsection
