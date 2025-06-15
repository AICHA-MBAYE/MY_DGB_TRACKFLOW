@extends('layouts.app')

{{-- Titre de la page --}}
@section('title', 'Liste des Agents Validés')

{{-- Titre principal du contenu --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34,155);">Liste des Agents Validés</h1>
@endsection

{{-- Sous-titre du contenu --}}
@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Voici la liste des agents dont l'inscription a été validée.</p>
@endsection


{{-- Contenu principal de la page --}}
@section('contenu')
{{-- Importation de la façade Auth pour les vérifications de rôle --}}
@php use Illuminate\Support\Facades\Auth; @endphp

<div class="bg-white p-6 rounded shadow-md max-w-7xl mx-auto">
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded shadow">
            {{-- En-tête du tableau --}}
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Prénom</th>
                    <th class="px-4 py-2 border">Nom</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Rôle</th>
                    <th class="px-4 py-2 border">Direction</th>
                    <th class="px-4 py-2 border">Statut</th>
                    {{-- La colonne Actions est affichée uniquement si l'utilisateur n'est PAS Chef de Service ou Directeur --}}
                    @if (!in_array(Auth::user()->role, ['chef_service', 'directeur']))
                        <th class="px-4 py-2 border w-1/5">Actions</th>
                    @endif
                </tr>
            </thead>
            {{-- Corps du tableau --}}
            <tbody>
                @forelse ($agents as $agent)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->id }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->prenom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->nom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->email }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ ucfirst(str_replace('_', ' ', $agent->role)) }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ ucfirst(str_replace('_', ' ', $agent->direction)) }}</td>
                        <td class="px-4 py-2 border" style="color: black;">
                            {{-- Affichage du statut de l'agent --}}
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Validé</span>
                        </td>
                        {{-- Les actions sont affichées uniquement si l'utilisateur n'est PAS Chef de Service ou Directeur --}}
                        @if (!in_array(Auth::user()->role, ['chef_service', 'directeur']))
                            <td class="px-4 py-2 border">
                                <div class="flex flex-wrap gap-2">
                                    {{-- Bouton Modifier --}}
                                    <a href="{{ route('agent.edit', $agent) }}" class="btn btn-primary bg-yellow-600 hover:bg-yellow-700">
                                        Modifier
                                    </a>

                                    {{-- Formulaire Supprimer --}}
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
                        {{-- Ajuste le colspan en fonction de la visibilité de la colonne Actions --}}
                        <td colspan="{{ !in_array(Auth::user()->role, ['chef_service', 'directeur']) ? '8' : '7' }}" class="text-center py-4 text-gray-500">Aucun agent validé trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
