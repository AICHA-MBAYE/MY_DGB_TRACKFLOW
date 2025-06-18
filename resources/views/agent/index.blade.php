@extends('layouts.app')

{{-- Titre de la page --}}
@section('title', 'Liste des agents')

{{-- Titre principal du contenu --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34,155);">Gestion des Agents</h1>
@endsection

{{-- Sous-titre du contenu --}}
@section('sousTitreContenu')
    <p class="text-center text-xl text-black">Gérez les inscriptions et les accès des agents</p>
@endsection

{{-- Contenu principal de la page --}}
@section('contenu')
@php use Illuminate\Support\Facades\Auth; @endphp

<div class="bg-white p-6 rounded shadow-md max-w-7xl mx-auto">
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded shadow">
            {{-- En-tête du tableau --}}
            <thead class="bg-blue-900 text-white">
                <tr>
                    
                    <th class="px-4 py-2 border">Prénom</th>
                    <th class="px-4 py-2 border">Nom</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Direction</th>
                    <th class="px-4 py-2 border">Division</th>
                    <th class="px-4 py-2 border">Rôle</th>
                    <th class="px-4 py-2 border">Statut</th>
                    <th class="px-4 py-2 border w-1/4">Actions d'Administration</th>
                </tr>
            </thead>
            {{-- Corps du tableau --}}
            <tbody>
                @forelse ($agents as $agent)
                    <tr class="hover:bg-gray-100">
                        
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->prenom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->nom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->email }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ ucfirst(str_replace('_', ' ', $agent->direction)) }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->division ? ucfirst(str_replace('_', ' ', $agent->division)) : '' }}</td>
                        <td class="px-4 py-2 border" style="color: black;">
                            @if ($agent->status === 'pending' && in_array(Auth::user()->role, ['super_admin', 'admin_sectoriel']))
                                <select id="role-select-{{ $agent->id }}" class="border border-gray-300 rounded px-2 py-1">
                                    <option value="">-- Choisir un rôle --</option>
                                    @if(Auth::user()->role === 'super_admin')
                                        <option value="super_admin">Super administrateur</option>
                                        <option value="admin_sectoriel">Administrateur sectoriel</option>
                                    @endif
                                    <option value="directeur">Directeur</option>
                                    <option value="chef_service">Chef de service</option>
                                    <option value="agent">Agent</option>
                                </select>
                            @else
                                {{ ucfirst(str_replace('_', ' ', $agent->role ?? 'Non défini')) }}
                            @endif
                        </td>
                        <td class="px-4 py-2 border" style="color: black;">
                            @if ($agent->status === 'pending')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                            @elseif ($agent->status === 'validated')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Validé</span>
                            @elseif ($agent->status === 'rejected')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejeté</span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inconnu</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border">
                            <div class="flex flex-col gap-2">
                                @if ($agent->status === 'pending')
                                    <form action="{{ route('agent.validateAndAssignPassword', $agent) }}" method="POST" class="inline-block"
                                        onsubmit="document.getElementById('hidden-role-{{ $agent->id }}').value = document.getElementById('role-select-{{ $agent->id }}').value;">
                                        @csrf
                                        <input type="hidden" name="role_to_assign" id="hidden-role-{{ $agent->id }}">
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                            Valider & Attribuer MDP
                                        </button>
                                    </form>

                                    <form action="{{ route('agent.reject', $agent) }}" method="POST"
                                        onsubmit="return confirm('Voulez-vous vraiment rejeter cet agent ?');" class="inline-block w-full mt-2">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger w-full">
                                            Rejeter
                                        </button>
                                    </form>
                                @elseif ($agent->status === 'validated' || $agent->status === 'rejected')
                                    <span class="text-gray-500 text-sm">Actions complétées ou non disponibles ici.</span>
                                @else
                                    <span class="text-gray-500 text-sm">Non applicable</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">Aucun agent trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('agent.register') }}" class="btn btn-primary">
            Inscrire un nouvel agent
        </a>
    </div>
</div>
@endsection