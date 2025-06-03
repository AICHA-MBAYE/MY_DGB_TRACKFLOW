@extends('layouts.app')

@section('content')
    <h2 class="mb-4 text-center" style="color: #2ecc71;"> Faire une demande d'absence</h2>

    <form action="{{ route('absence.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="date_debut" class="form-label">Date de début</label>
            <input type="date" name="date_debut" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="date_fin" class="form-label">Date de fin</label>
            <input type="date" name="date_fin" class="form-control" required>
        </div>



        <div class="form-group">
    <label for="justificatif">Justificatif (PDF max 2Mo, optionnel)</label>
    <input type="file" name="justificatif" id="justificatif" accept="application/pdf">
    @error('justificatif')
        <div style="color:red">{{ $message }}</div>
    @enderror
    </div>

        <div class="mb-3">
            <label for="motif" class="form-label">Motif</label>
            <textarea name="motif" class="form-control" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Soumettre</button>
    </form>
@endsection
