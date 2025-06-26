@extends('layouts.app')

{{-- Titre de la page --}}
@section('title', 'Liste des Agents Validés')

{{-- Titre principal du contenu --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34,155);">Liste des Agents Validés</h1>
@endsection

{{-- Contenu principal de la page --}}
@section('contenu')
@php use Illuminate\Support\Facades\Auth; @endphp

<div class="bg-white p-6 rounded shadow-md max-w-7xl mx-auto">
    {{-- Champ de recherche unique --}}
    <form method="GET" action="{{ route('agent.validated_index') }}" class="mb-6 flex flex-wrap gap-4 items-end justify-between">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" class="border border-gray-300 rounded px-3 py-2 w-full" placeholder="Rechercher par division, direction, rôle, nom ou prénom...">
        </div>
        <div>
            <button type="submit" class="bg-blue-400 hover:bg-blue-600 text-white font-bold px-4 py-2 rounded">Rechercher</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded shadow mx-auto">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;">Prénom</th>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;">Nom</th>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agents as $agent)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->prenom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->nom }}</td>
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('agent.validatedDetails', $agent) }}" class="text-blue-400 hover:text-blue-600 underline font-bold">
                                Voir détails
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">Aucun agent validé trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- Suppression du bouton retour/flèche en bas de page --}}
</div>
@endsection
