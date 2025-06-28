@extends('layouts.app')

@section('contenu')
<div class="container mt-4">
    {{-- Add a row and a column for better control over width and centering --}}
    <div class="row justify-content-center"> {{-- Centers the column horizontally --}}
        <div class="col-md-8 col-lg-6"> {{-- Make the column smaller on medium and large screens --}}
            <div class="card shadow">
                <div class="card-header" style="background-color: #002147; color: #fff;">
                    <h3 class="mb-0">Détail de la demande d'absence</h3>
                </div>
                <div class="card-body">
                    {{-- Using a Definition List for better semantics and control over layout --}}
                    <dl class="row mb-4">
                        <dt class="col-sm-4">Agent</dt>
                        <dd class="col-sm-8">{{ $demande->agent->prenom ?? '' }} {{ $demande->agent->nom ?? '' }}</dd>

                        <dt class="col-sm-4">Date début</dt>
                        <dd class="col-sm-8">{{ $demande->date_debut }}</dd>

                        <dt class="col-sm-4">Date fin</dt>
                        <dd class="col-sm-8">{{ $demande->date_fin }}</dd>

                        <dt class="col-sm-4">Motif</dt>
                        <dd class="col-sm-8">{{ $demande->motif }}</dd>

                        <dt class="col-sm-4">Statut</dt>
                        <dd class="col-sm-8">{{ $demande->statut ?? '-' }}</dd>

                        <dt class="col-sm-4">Date de soumission</dt>
                        <dd class="col-sm-8">{{ $demande->created_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Date de dernière modification</dt>
                        <dd class="col-sm-8">{{ $demande->updated_at->format('d/m/Y H:i') }}</dd>

                        <dt class="col-sm-4">Statut Chef</dt>
                        <dd class="col-sm-8">
                            {{ $demande->etat_chef ?? '-' }}
                            @if(!empty($demande->date_traitement_chef))
                                <br>
                                <small class="text-muted">Traité le {{ \Carbon\Carbon::parse($demande->date_traitement_chef)->format('d/m/Y H:i') }}</small>
                            @endif
                        </dd>
                        <dt class="col-sm-4">Statut Directeur</dt>
                        <dd class="col-sm-8">
                            {{ $demande->etat_directeur ?? '-' }}
                            @if(!empty($demande->date_traitement_directeur))
                                <br>
                                <small class="text-muted">Traité le {{ \Carbon\Carbon::parse($demande->date_traitement_directeur)->format('d/m/Y H:i') }}</small>
                            @endif
                        </dd>
                    </dl>

                    <a href="{{ $retour }}" class="btn" style="background-color: #002147; color: #fff;">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
