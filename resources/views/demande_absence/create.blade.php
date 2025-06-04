@extends('layouts.app')

@section('contenu')
    <h2 class="mb-4 text-center" style="color: #181daa;"> Faire une demande d'absence</h2>

    <form action="{{ route('demande_absence.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="date_debut" class="form-label">Date de début</label>
           <input type="date" name="date_debut" class="form-control" min="{{ date('Y-m-d') }}" required>

        </div>

        <div class="mb-3">
            <label for="date_fin" class="form-label">Date de fin</label>
            <input type="date" name="date_fin" class="form-control" min="{{ date('Y-m-d') }}" required>

        </div>



        <div class="form-group">
    <label for="justificatif">Justificatif (PDF max 2Mo, optionnel)</label>
    <input type="file" name="justificatif" id="justificatif" accept="application/pdf" class="form-control  text-black" >
    @error('justificatif')
        <div style="color:red">{{ $message }}</div>
    @enderror
    </div>

        <div class="mb-3">
            <label for="motif" class="form-label">Motif</label>
            <textarea name="motif" class="form-control  text-black" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Soumettre</button>
    </form>
@endsection
