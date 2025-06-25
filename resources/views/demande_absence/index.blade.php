@extends('layouts.app')

@section('title', 'Liste absence')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Mes demandes d'absence</h1>
@endsection

@section('contenu')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('demande_absence.stats') }}" class="btn btn-info" style="background:#003366; color:#fff;">Voir statistiques</a>
    </div>

    @if ($demandes->isEmpty())
        <p class="text-center text-white bg-dark p-3 mt-4 rounded">Vous n'avez encore aucune demande d'absence.</p>
    @else
        <!-- Nav tabs for sections -->
        <ul class="nav nav-tabs mt-4" id="absenceTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="soumises-tab" data-bs-toggle="tab" data-bs-target="#soumises" type="button" role="tab" aria-controls="soumises" aria-selected="true" style="color: #003366; font-weight: bold;">Demandes non traitées</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="traitées-tab" data-bs-toggle="tab" data-bs-target="#traitées" type="button" role="tab" aria-controls="traitées" aria-selected="false" style="color: #003366; font-weight: bold;">Demandes traitées</button>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content mt-3 p-3 border rounded" style="border-color: #dee2e6; background-color: #f8f9fa;">
            {{-- Section : Demandes soumises (brouillon, en attente) --}}
            <div class="tab-pane fade show active" id="soumises" role="tabpanel" aria-labelledby="soumises-tab">
                @php
                    $demandesSoumises = $demandes->filter(function ($demande) {
                        return $demande->statut === 'brouillon' || ($demande->etat_chef === 'acceptée' && $demande->etat_directeur === 'en_attente');
                    });
                @endphp

                @if ($demandesSoumises->isEmpty())
                    <p class="text-center text-secondary">Aucune demande en attente ou brouillon.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-white" style="background-color: #123a3c; border-radius: 8px;">
                            <thead>
                                <tr>
                                    <th style="background-color:#003366; color:#fff;">Date de début</th>
                                    <th style="background-color:#003366; color:#fff;">Date de fin</th>
                                    <th style="background-color:#003366; color:#fff;">Motif</th>
                                    <th style="background-color:#003366; color:#fff;">Statut</th>
                                    <th style="background-color:#003366; color:#fff;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($demandesSoumises as $demande)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</td>
                                        <td>{{ $demande->motif }}</td>
                                        <td>
                                            @if($demande->statut === 'brouillon')
                                                <span style="color:#f39c12;font-weight:bold;">Brouillon</span>
                                            @elseif($demande->etat_chef === 'acceptée' && $demande->etat_directeur === 'en_attente')
                                                <span style="color:#f39c12;font-weight:bold;">Soumise</span> {{-- Changed color for consistency --}}
                                            @else
                                                <span>{{ ucfirst($demande->statut) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                                {{-- Bouton "Soumettre" à gauche (seulement pour les brouillons) --}}
                                                <div>
                                                    @if($demande->statut === 'brouillon')
                                                        <form action="{{ route('demande_absence.submit', $demande->id) }}" method="POST" style="display:inline-block;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Soumettre cette demande ?');" style="color: #fff;">Soumettre</button>
                                                        </form>
                                                    @endif
                                                </div>

                                                {{-- Icônes "Modifier", "Supprimer", "Voir détails" à droite (UNIQUEMENT pour les brouillons) --}}
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    @if($demande->statut === 'brouillon') {{-- Rétablissement de la condition originale --}}
                                                       <a href="{{ route('demande_absence.edit', $demande->id) }}"
                                                            title="Modifier"
                                                            class="btn btn-sm btn-icon"
                                                            style="background:#2ecc71;">
                                                            <i class="fas fa-pen"></i>
                                                            </a>
                                                        {{-- Formulaire de suppression stylisé comme une icône --}}
                                                        <a href="{{ route('demande_absence.destroy', $demande->id) }}" method="POST"
                                                            title="Supprimer"
                                                            class="btn btn-sm btn-icon"
                                                            style="background:#e74c3c;" onclick="return confirm('Voulez-vous vraiment supprimer cette demande ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    @endif
                                                   <a href="{{ route('demande_absence.show', $demande->id) }}"
                                                        title="Voir détails"
                                                        class="btn btn-sm btn-icon"
                                                        style="background:#00509e;">
                                                        <i class="fas fa-circle-info"></i>
                                                        </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Section : Demandes traitées (validée, rejetée) --}}
            <div class="tab-pane fade" id="traitées" role="tabpanel" aria-labelledby="traitées-tab">
                @php
                    $demandesTraitées = $demandes->filter(function ($demande) {
                        return ($demande->etat_chef === 'rejetée' || $demande->etat_directeur === 'rejetée') ||
                               ($demande->etat_chef === 'acceptée' && $demande->etat_directeur === 'acceptée');
                    });
                @endphp

                @if ($demandesTraitées->isEmpty())
                    <p class="text-center text-secondary">Aucune demande traitée pour le moment.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-white" style="background-color: #123a3c; border-radius: 8px;">
                            <thead>
                                <tr>
                                    <th style="background-color:#003366; color:#fff;">Date de début</th>
                                    <th style="background-color:#003366; color:#fff;">Date de fin</th>
                                    <th style="background-color:#003366; color:#fff;">Motif</th>
                                    <th style="background-color:#003366; color:#fff;">Statut</th>
                                    <th style="background-color:#003366; color:#fff;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($demandesTraitées as $demande)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</td>
                                        <td>{{ $demande->motif }}</td>
                                        <td>
                                            @if($demande->etat_chef === 'rejetée' || $demande->etat_directeur === 'rejetée')
                                                <span style="color:#e74c3c;font-weight:bold;">Rejetée</span>
                                            @elseif($demande->etat_chef === 'acceptée' && $demande->etat_directeur === 'acceptée')
                                                <span style="color:#2ecc71;font-weight:bold;">Validée</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                                                @if(
                                                    $demande->pdf_path &&
                                                    ( ($demande->etat_chef === 'acceptée' && $demande->etat_directeur === 'acceptée') ||
                                                      ($demande->etat_chef === 'rejetée' || $demande->etat_directeur === 'rejetée') )
                                                )
                                                    <a href="{{ route('agent.download_acte', $demande->id) }}" class="btn btn-sm btn-secondary" style="color: #fff;">Télécharger l’acte</a>
                                                @endif
                                                <a href="{{ route('demande_absence.show', $demande->id) }}"
                                                    title="Voir détails"
                                                    class="btn btn-sm btn-icon"
                                                    style="background:#00509e;">
                                                   <i class="fas fa-circle-info"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('demande_absence.create') }}" class="btn btn-secondary" style="color: #fff;">Faire une nouvelle demande</a>
    </div>

@endsection

<style>
    /* Styles généraux de la table */
    table {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 1rem;
        width: 100%; /* Assurer que la table prend toute la largeur */
    }
    thead th {
        border-bottom: 2px solid #2ecc71;
        color: #fff; /* Texte blanc dans l'en-tête */
        background-color:#003366; /* Arrière-plan de l'en-tête */
        padding: 12px 15px; /* Padding pour les en-têtes */
        text-align: left;
    }
    tbody tr {
        color: #fff; /* Texte blanc pour le corps du tableau */
    }
    tbody tr:hover {
        background-color: #1e4d4f; /* Assombrir au survol */
    }
    tbody td {
        padding: 10px 15px; /* Padding pour les cellules du corps */
        vertical-align: middle; /* Aligner le contenu au milieu verticalement */
    }

    /* Styles des boutons génériques */
    .btn {
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex; /* Utiliser flex pour l'alignement interne */
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-1px); /* Léger effet de survol */
    }

    /* Styles des alertes */
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    .btn-close {
        /* Styles Bootstrap par défaut pour le bouton de fermeture */
    }

    /* Styles des boutons spécifiques */
    .btn-info {
        background-color: #003366;
        color: #fff;
        border: none;
    }
    .btn-info:hover {
        background-color: #002244;
    }

    .btn-success {
        background-color: #2ecc71;
        color: #fff; /* Texte blanc pour le bouton soumettre */
        border: none;
    }
    .btn-success:hover {
        background-color: #27ae60;
    }

    .btn-secondary {
        background-color: #123a3c;
        border: 1px solid #2ecc71;
        color: #2ecc71; /* Texte vert pour le bouton secondaire */
    }
    .btn-secondary:hover {
        background-color: #2ecc71;
        color: #123a3c;
    }

    /* Styles pour les onglets */
    .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
        color: #003366; /* Couleur du texte des onglets inactifs */
        background-color: #e9ecef; /* Arrière-plan des onglets inactifs */
        padding: 10px 20px; /* Padding pour les onglets */
    }
    .nav-tabs .nav-link.active {
        color: #fff; /* Couleur du texte de l'onglet actif */
        background-color: #003366; /* Arrière-plan de l'onglet actif */
        border-color: #003366 #003366 #fff; /* Bordures de l'onglet actif */
    }
    .tab-content {
        border-top: none; /* Supprimer la bordure supérieure pour s'aligner avec les onglets */
    }
.btn-icon i {
    font-size: 1.2em;
    line-height: 1;
    margin: 0;
    padding: 0;
}
    /* Nouveau style pour les boutons d'icônes circulaires */
   .btn-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    color: #fff;
    font-size: 1.2em; /* plus petit que 2em sinon ça déborde */
    text-decoration: none;
    border: none;
    cursor: pointer;
    padding: 0;
    box-shadow: 0 6px 12px rgba(0,0,0,0.4);
    background-color: #333; /* tu peux personnaliser si besoin */
}
    .btn-icon:hover {
        transform: scale(1.1); /* Zoom légèrement plus grand au survol */
    }
</style>
