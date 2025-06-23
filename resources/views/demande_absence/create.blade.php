{{-- filepath: resources/views/demande_absence/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Demande absence')


@section('contenu')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('demande_absence.store') }}" method="POST" enctype="multipart/form-data" class="stylish-form" style="max-width: 600px; margin: 0 auto;">
            @csrf

            <h5 class="mb-3 text-center" style="color:#003366;">Nouvelle demande d'absence</h5>

            <div class="mb-3">
                <label for="date_debut" class="form-label">Date de début</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="date_fin" class="form-label">Date de fin</label>
                <input type="date" name="date_fin" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="justificatif" class="form-label">Justificatif (PDF max 2Mo, optionnel)</label>
                <input type="file" name="justificatif" id="justificatif" accept="application/pdf" class="form-control">
                @error('justificatif')
                    <div style="color:red">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="motif" class="form-label">Motif</label>
                <textarea name="motif" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
        </form>
    </div>
@endsection
