@extends('layouts.app')

@section('title', 'Accueil')

@section('titreContenu')
    <span style="color: black;">Bienvenue</span>
@endsection

@section('sousTitreContenu')
    <span style="color: black;">Système de gestion des agents - MY_DGB_TRACKFLOW</span>
@endsection


@section('contenu')
    <div class="text-center py-10">
        <h1 class="text-4xl font-bold text-gray-800 mb-4" style="color: black;">Bienvenue dans MY_DGB_TRACKFLOW</h1>
        <p class="text-lg text-gray-600" style="color: black;">
            Plateforme de gestion et de suivi des agents de la Direction Générale du Budget.
        </p>
    </div>
@endsection
