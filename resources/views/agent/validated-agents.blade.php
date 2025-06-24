@extends('layouts.app')

{{-- Titre de la page --}}
@section('title', 'Liste des Agents Validés')

{{-- Titre principal du contenu --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34,155);">Liste des Agents Validés</h1>
@endsection

on

{{-- Contenu principal de la page --}}
@section('contenu')
@php use Illuminate\Support\Facades\Auth; @endphp

<div class="bg-white p-6 rounded shadow-md max-w-7xl mx-auto">
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded shadow mx-auto">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;" >Prénom</th>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;">Nom</th>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;">Email</th>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;">Rôle</th>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;">Direction</th>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;">Division</th>
                    {{-- La colonne Actions est affichée uniquement si l'utilisateur connecté est super_admin ou admin_sectoriel --}}
                    @if (in_array(Auth::user()->role, ['super_admin', 'admin_sectoriel']))
                        <th class="px-4 py-2 border w-1/5" style="background-color:#003366; color:#fff;" >Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($agents as $agent)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->prenom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->nom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->email }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ ucfirst(str_replace('_', ' ', $agent->role)) }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ ucfirst(str_replace('_', ' ', $agent->direction)) }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ ucfirst(str_replace('_', ' ', $agent->division ?? '')) }}</td>
                        {{-- La cellule d'actions est affichée uniquement si l'utilisateur connecté est super_admin ou admin_sectoriel --}}
                        @if (in_array(Auth::user()->role, ['super_admin', 'admin_sectoriel']))
                            <td class="px-4 py-2 border">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('agent.edit', $agent) }}" class="btn btn-primary bg-yellow-600 hover:bg-yellow-700">
                                        Modifier
                                    </a>
                                    <form action="{{ route('agent.destroy', $agent) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cet agent ?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        {{-- Colspan ajusté: 6 colonnes fixes (Prénom, Nom, Email, Rôle, Direction, Division) + 1 colonne d'Actions (si visible).
                             Si l'utilisateur est super_admin ou admin_sectoriel, la colonne Actions est affichée (7 colonnes).
                             Si l'utilisateur est chef_service ou directeur, la colonne Actions n'est pas affichée (6 colonnes).
                        --}}
                        <td colspan="{{ in_array(Auth::user()->role, ['super_admin', 'admin_sectoriel']) ? '7' : '6' }}" class="text-center py-4 text-gray-500">Aucun agent validé trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
