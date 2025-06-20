@extends('layouts.app')

@section('title', 'Liste des agents')

@section('contenu')
    <h1 class="mb-4">Liste des agents</h1>

    <form method="GET" class="mb-3">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Rechercher un agent..." class="form-control" style="width: 300px; display: inline;">
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Direction</th>
                <th>Division</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($agents as $agent)
                <tr>
                    <td>{{ $agent->prenom }}</td>
                    <td>{{ $agent->nom }}</td>
                    <td>{{ $agent->direction ?? '-' }}</td>
                    <td>{{ $agent->division ?? '-' }}</td>
                    <td>
                        <a href="{{ route('chef.agent.stats', $agent->id) }}" class="btn btn-info btn-sm">Voir statistiques</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Aucun agent trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
