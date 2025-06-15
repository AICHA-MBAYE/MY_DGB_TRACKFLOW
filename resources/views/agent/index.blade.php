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
                    <th class="px-4 py-2 border">Statut</th> {{-- Ajout de la colonne Statut --}}
                    <th class="px-4 py-2 border w-1/4">Actions d'Administration</th> {{-- Élargissement de la colonne Actions --}}
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
                            <div class="flex flex-wrap gap-2">
                                @if ($agent->status === 'pending')
                                    {{-- Formulaire pour Attribuer un mot de passe et Valider --}}
                                    <form action="{{ route('agent.validateAndAssignPassword', $agent) }}" method="POST"
                                        onsubmit="return confirm('Voulez-vous valider cet agent et lui attribuer un mot de passe ?');" class="inline-block">
                                        @csrf
                                        {{-- Note: Nous pouvons simuler l'attribution de mot de passe ici pour cet exemple --}}
                                        <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white">
                                            Valider & Attribuer MDP
                                        </button>
                                    </form>

                                    {{-- Formulaire pour Rejeter --}}
                                    <form action="{{ route('agent.reject', $agent) }}" method="POST"
                                        onsubmit="return confirm('Voulez-vous vraiment rejeter cet agent ?');" class="inline-block">
                                        @csrf
                                        @method('PUT') {{-- Utilisation de PUT ou PATCH pour une mise à jour de statut --}}
                                        <button type="submit" class="btn btn-danger">
                                            Rejeter
                                        </button>
                                    </form>
                                @elseif ($agent->status === 'validated')
                                    <span class="text-gray-500 text-sm">Actions complétées</span>
                                @else
                                    <span class="text-gray-500 text-sm">Non applicable</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Aucun agent trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Bouton pour ajouter un nouvel agent (qui est maintenant "Inscription d'un agent") --}}
    <div class="mt-6 flex justify-end">
        <a href="{{ route('agent.register') }}" class="btn btn-primary">
            Inscrire un nouvel agent
        </a>
    </div>
</div>

<script>
    // Ajout d'un script pour masquer les alertes de confirmation natives.
    // Cela devrait être remplacé par un modal UI personnalisé pour une meilleure UX.
    // Pour l'instant, on laisse l'attribut onsubmit="" avec un retour false si un modal personnalisé n'est pas implémenté.
</script>
@endsection
