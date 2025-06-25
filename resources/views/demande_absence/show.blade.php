@extends('layouts.app')

@section('contenu')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header" style="background-color: #002147; color: #fff;">
            <h3 class="mb-0">Détail de la demande d'absence</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered mb-4">
                <tr>
                    <th>Agent</th>
                    <td>{{ $demande->agent->prenom ?? '' }} {{ $demande->agent->nom ?? '' }}</td>
                </tr>
                <tr>
                    <th>Date début</th>
                    <td>{{ $demande->date_debut }}</td>
                </tr>
                <tr>
                    <th>Date fin</th>
                    <td>{{ $demande->date_fin }}</td>
                </tr>
                <tr>
                    <th>Motif</th>
                    <td>{{ $demande->motif }}</td>
                </tr>
                <tr>
                    <th>Statut</th>
                    <td>{{ $demande->statut ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Date de soumission</th>
                    <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Date de dernière modification</th>
                    <td>{{ $demande->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
               <tr>
    <th>Statut Chef</th>
    <td>
        {{ $demande->etat_chef ?? '-' }}
        @if(!empty($demande->date_traitement_chef))
            <br>
            <small class="text-muted">Traité le {{ \Carbon\Carbon::parse($demande->date_traitement_chef)->format('d/m/Y H:i') }}</small>
        @endif
    </td>
</tr>
<tr>
    <th>Statut Directeur</th>
    <td>
        {{ $demande->etat_directeur ?? '-' }}
        @if(!empty($demande->date_traitement_directeur))
            <br>
            <small class="text-muted">Traité le {{ \Carbon\Carbon::parse($demande->date_traitement_directeur)->format('d/m/Y H:i') }}</small>
        @endif
    </td>
</tr>
            </table>
            <a href="{{ $retour }}" class="btn" style="background-color: #002147; color: #fff;">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
</div>
@endsection
