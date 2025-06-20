@extends('layouts.app')

@section('title', 'Liste absence')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Mes demandes d'absence</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Voici la liste de vos demandes d'absence</p>
@endsection

@section('contenu')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    @if ($demandes->isEmpty())
        <p class="text-center text-black">Vous n'avez encore aucune demande d'absence.</p>
    @else
    <div class="text-center mt-4">
    <a href="{{ route('demande_absence.stats') }}" class="btn btn-info">Voir statistiques</a>
</div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-black" style="background-color: #123a3c; border-radius: 8px;">
                <thead>
                    <tr>
                         <th style="background-color:#003366; color:#fff;">Date de début</th>
                        <th style="background-color:#003366; color:#fff;">Date de fin</th>
                        <th style="background-color:#003366; color:#fff;">Motif</th>
                         <th style="background-color:#003366; color:#fff;">Division</th>
                        <th style="background-color:#003366; color:#fff;">Statut chef</th>
                        <th style="background-color:#003366; color:#fff;">Statut directeur</th>
                        <th style="background-color:#003366; color:#fff;">Statut</th>
                        <th style="background-color:#003366; color:#fff;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($demandes as $demande)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</td>
                            <td>{{ $demande->motif }}</td>
                            <td>{{ $demande->agent->division ?? '-' }}</td>
                            <td style="color:
                                @if ($demande->etat_chef === 'en_attente') #f39c12
                                @elseif ($demande->etat_chef === 'acceptée') #2ecc71
                                @elseif ($demande->etat_chef === 'rejetée') #e74c3c
                                @else #fff
                                @endif;">
                                {{ ucfirst($demande->etat_chef) }}
                            </td>
                            <td style="color:
                                @if ($demande->etat_directeur === 'en_attente') #f39c12
                                @elseif ($demande->etat_directeur === 'acceptée') #2ecc71
                                @elseif ($demande->etat_directeur === 'rejetée') #e74c3c
                                @else #fff
                                @endif;">
                                {{ ucfirst($demande->etat_directeur) }}
                            </td>
                            <td>{{ ucfirst($demande->statut) }}</td>
                            <td>
                                @if($demande->statut === 'brouillon')
                                    <form action="{{ route('demande_absence.submit', $demande->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Soumettre cette demande ?');">Soumettre</button>
                                    </form>
                                    <a href="{{ route('demande_absence.edit', $demande->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                                    <form action="{{ route('demande_absence.destroy', $demande->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette demande ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                @endif
                                @if(
                                    $demande->pdf_path &&
                                    $demande->etat_chef !== 'en_attente' &&
                                    $demande->etat_directeur !== 'en_attente'
                                )
                                    <a href="{{ route('agent.download_acte', $demande->id) }}" class="btn btn-sm btn-secondary">Télécharger l’acte</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('demande_absence.create') }}" class="btn btn-secondary">Faire une nouvelle demande</a>
    </div>

@endsection

<style>
    table {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 1rem;
    }
    thead th {
        border-bottom: 2px solid #2ecc71;
        color: #2ecc71;
    }
    tbody tr:hover {
        background-color: #1e4d4f;
    }
    .btn-primary {
        background-color: #2ecc71;
        border: none;
    }
    .btn-primary:hover {
        background-color: #27ae60;
    }
    .btn-secondary {
        background-color: #123a3c;
        border: 1px solid #2ecc71;
        color: #2ecc71;
    }
    .btn-secondary:hover {
        background-color: #2ecc71;
        color: #123a3c;
    }
</style>
