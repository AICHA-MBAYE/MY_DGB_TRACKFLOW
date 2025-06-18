@extends('layouts.app')

@section('title', 'Liste des Agents Validés')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34,155);">Liste des Agents Validés</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Voici la liste des agents dont l'inscription a été validée.</p>
@endsection

@section('contenu')
@php use Illuminate\Support\Facades\Auth; @endphp

<div class="bg-white p-6 rounded shadow-md max-w-7xl mx-auto">
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded shadow">
            <thead class="bg-blue-900 text-white">
                <tr>
                    
                    <th class="px-4 py-2 border">Prénom</th>
                    <th class="px-4 py-2 border">Nom</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Rôle</th>
                    <th class="px-4 py-2 border">Direction</th>
                    <th class="px-4 py-2 border">Division</th>
                    @if (!in_array(Auth::user()->role, ['chef_service', 'directeur']))
                        <th class="px-4 py-2 border w-1/5">Actions</th>
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
                        <td class="px-4 py-2 border" style="color: black;">{{ ucfirst(str_replace('_', ' ', $agent->division ?? 'N/A')) }}</td>
                        @if (!in_array(Auth::user()->role, ['chef_service', 'directeur']))
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
                        <td colspan="{{ !in_array(Auth::user()->role, ['chef_service', 'directeur']) ? '9' : '8' }}" class="text-center py-4 text-gray-500">Aucun agent validé trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection