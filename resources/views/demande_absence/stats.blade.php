{{-- filepath: resources/views/demande_absence/stats.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistiques absences')

@section('contenu')
<div style="max-width: 800px; margin: 0 auto;">
    <h1 class="text-center mb-4" style="color:#003366; font-weight: bold;">
        <i class="bi bi-bar-chart-fill"></i> Statistiques d'absence
    </h1>
    @if(isset($agent))
        <h2 class="text-center mb-3" style="color:#00509e;">
            Statistiques de {{ $agent->prenom }} {{ $agent->nom }}
        </h2>
    @endif

    <form method="GET" class="mb-4 d-flex align-items-center justify-content-end gap-2" style="background:#f8fafc; padding: 1rem 1.5rem; border-radius: 10px;">
        <label for="annee" class="mb-0 me-2" style="font-weight:600;">Choisir l'année :</label>
        <select name="annee" id="annee" class="form-select w-auto" onchange="this.form.submit()">
            @foreach($annees as $a)
                <option value="{{ $a }}" @if($a == $annee) selected @endif>{{ $a }}</option>
            @endforeach
        </select>
    </form>

    <div class="card shadow mb-4">
        <div class="card-header" style="background:#003366; color:#fff;">
            <i class="bi bi-calendar-event"></i>
            Mois en cours :
            <span style="font-weight: bold; font-size: 1.1em;">
                {{ \Carbon\Carbon::now()->locale('fr')->monthName }} {{ \Carbon\Carbon::now()->year }}
            </span>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead style="background:#e9ecef;">
                    <tr>
                        <th>Mois</th>
                        <th>Nombre de jours d'absence</th>
                        <th>Nombre de demandes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats as $mois => $data)
                        <tr @if($mois == now()->month) style="background: #e3f0ff; font-weight: bold;" @endif>
                            <td>
                                {{ \Carbon\Carbon::create()->month($mois)->locale('fr')->monthName }}
                                @if($mois == now()->month)
                                    <span class="badge bg-primary ms-2">Mois en cours</span>
                                @endif
                            </td>
                            <td>{{ $data['nb_jours'] }}</td>
                            <td>{{ $data['nb_demandes'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
