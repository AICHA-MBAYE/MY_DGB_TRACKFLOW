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

@section('contenu')
<div class="flex justify-center items-center min-h-screen bg-gray-100 py-4 px-2">
    <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-2xl mx-auto border border-gray-200">
        <h2 class="text-center text-4xl font-bold text-gray-800 mb-6" style="font-family: 'Dancing Script', cursive; line-height: 1.2;">
            {{ $agent->prenom }} <br class="sm:hidden"/> {{ $agent->nom }}
        </h2>
        <div class="space-y-3 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Email :</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200 break-words">{{ $agent->email }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Rôle :</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200">{{ ucfirst(str_replace('_', ' ', $agent->role)) }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Direction :</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200">{{ ucfirst(str_replace('_', ' ', $agent->direction)) }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Division :</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200">{{ $agent->division ? ucfirst(str_replace('_', ' ', $agent->division)) : '' }}</p>
            </div>
            {{-- Afficher la date et l'heure de validation et Validé par si l'agent est validé --}}
            @if($agent->status === 'validated')
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Date et heure de validation :</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200">
                    {{ $validationEntry && $validationEntry->validated_at ? $validationEntry->validated_at->format('d/m/Y H:i') : 'N/A' }}
                </p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-1">Validé par :</label>
                <p class="text-gray-900 text-base font-medium bg-gray-50 p-2 rounded-md border border-gray-200">
                    @if(isset($validatorAgent) && $validatorAgent)
                        {{ $validatorAgent->prenom }} {{ $validatorAgent->nom }} 
                        <span class="text-xs text-gray-500">({{ ucfirst(str_replace('_', ' ', $validatorAgent->role ?? 'N/A')) }})</span>
                    @else
                        N/A
                    @endif
                </p>
            </div>
            @endif
        </div>
        @if(in_array(Auth::user()->role, ['super_admin', 'admin_sectoriel']))
        <div class="flex justify-center gap-20 mt-4"> <!-- gap-20 pour encore plus d'espace -->
            <a href="{{ route('agent.edit', $agent) }}" class="p-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-full shadow-xl flex items-center text-2xl border-4 border-white hover:border-yellow-300 focus:ring-4 focus:ring-yellow-200 outline-none" style="width:56px; height:56px;" title="Modifier">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form action="{{ route('agent.destroy', $agent) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet agent ?');" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-3 bg-red-700 hover:bg-red-900 text-white rounded-full shadow-xl flex items-center text-2xl border-4 border-white hover:border-red-300 focus:ring-4 focus:ring-red-200 outline-none" style="width:56px; height:56px;" title="Supprimer">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
        @endif
        <div class="mt-6 pt-3 border-t border-gray-200 text-center">
            <a href="{{ route('agent.validated_index') }}" class="inline-flex items-center justify-center bg-gradient-to-br from-[#191970] to-blue-900 hover:from-blue-900 hover:to-blue-950 text-white font-bold p-3 rounded-full shadow-xl transition duration-200 ease-in-out text-3xl border-4 border-white hover:border-blue-300 focus:ring-4 focus:ring-blue-200 outline-none" style="width:56px; height:56px;" title="Retour">
                <i class="fa-solid fa-circle-arrow-left"></i>
            </a>
        </div>
    </div>
</div>
@endsection
