@extends('layouts.app')

@section('title', 'Demande absence')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Faire une demande d'absence</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Remplissez les informations ci-dessous</p>
@endsection

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
    <form action="{{ route('demande_absence.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="date_debut" class="form-label">Date de début</label>
           <input type="date" name="date_debut" class="form-control"  required>

        </div>

        <div class="mb-3">
            <label for="date_fin" class="form-label">Date de fin</label>
            <input type="date" name="date_fin" class="form-control"      required>

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

        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
    </form>
@endsection
