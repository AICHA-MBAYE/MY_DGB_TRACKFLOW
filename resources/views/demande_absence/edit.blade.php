@extends('layouts.app')

@section('title', 'Modification absence')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Modifier la demande d'absence</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Réctifiez les informations ci-dessous</p>
@endsection

@section('contenu')
<div class="container">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('demande_absence.update', $demande->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="date_debut" class="form-label">Date de début</label>
            <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut', $demande->date_debut) }}" required>
        </div>

        <div class="mb-3">
            <label for="date_fin" class="form-label">Date de fin</label>
            <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin', $demande->date_fin) }}" required>
        </div>

        <div class="mb-3">
            <label for="motif" class="form-label">Motif</label>
            <textarea name="motif" class="form-control" rows="3" required>{{ old('motif', $demande->motif) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="justificatif" class="form-label">Justificatif (PDF, optionnel)</label>
            @if ($demande->justificatif)
                <p>Fichier actuel : <a href="{{ asset('storage/' . $demande->justificatif) }}" target="_blank">Voir le fichier</a></p>
            @endif
            <input type="file" name="justificatif" class="form-control" accept="application/pdf">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('demande_absence.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
