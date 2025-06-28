@extends('layouts.app')

{{-- Titre de la page --}}
@section('title', 'Gestion des agents')

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
        <table class="min-w-full border border-gray-300 rounded shadow mx-auto">
            {{-- En-tête du tableau --}}
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;" >Prénom</th>
                    <th class="px-4 py-2 border" style="background-color:#003366; color:#fff;" >Nom</th>
                    <th class="px-4 py-2 border w-1/4" style="background-color:#003366; color:#fff;" >Actions</th>
                </tr>
            </thead>
            {{-- Corps du tableau --}}
            <tbody>
                @forelse ($agents as $agent)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->prenom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->nom }}</td>
                        <td class="px-4 py-2 border">
                            @if($agent->status === 'validated')
                                <a href="{{ route('agent.validatedDetails', $agent->id) }}" class="text-blue-500 hover:text-blue-700 underline" style="color: #87CEEB;">
                                    Voir détails
                                </a>
                            @else
                                <a href="{{ route('agent.show', $agent->id) }}" class="text-blue-500 hover:text-blue-700 underline" style="color: #87CEEB;">
                                    Voir détails
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">Aucun agent trouvé.</td>
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