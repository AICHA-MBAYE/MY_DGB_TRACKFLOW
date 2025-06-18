@extends('layouts.app')

@section('title', 'Statistiques absences')

@section('contenu')
    <h1 class="text-2xl font-bold mb-4">Statistiques d'absence</h1>
    @if(isset($agent))
    <h2>Statistiques de {{ $agent->prenom }} {{ $agent->nom }}</h2>
@endif
    <form method="GET" class="mb-4">
        <label for="annee">Choisir l'année :</label>
        <select name="annee" id="annee" onchange="this.form.submit()">
            @foreach($annees as $a)
                <option value="{{ $a }}" @if($a == $annee) selected @endif>{{ $a }}</option>
            @endforeach
        </select>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mois</th>
                <th>Nombre de jours d'absence</th>
                <th>Nombre de demandes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats as $mois => $data)
                <tr>
                    <td>{{ \Carbon\Carbon::create()->month($mois)->locale('fr')->monthName }}</td>
                    <td>{{ $data['nb_jours'] }}</td>
                    <td>{{ $data['nb_demandes'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
