<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acte Administratif</title>
</head>
<body>
    <h1>Direction Générale du Budget - Acte Administratif</h1>
    <p>Nom de l’agent: {{ $agent->nom }}</p>
    <p>Date début: {{ $demande->date_debut }}</p>
    <p>Date fin: {{ $demande->date_fin }}</p>
    <p>Motif: {{ $demande->motif }}</p>
    <p>Statut chef: {{ $demande->etat_chef }}</p>
<p>Statut directeur: {{ $demande->etat_directeur }}</p>
@if($demande->etat_chef === 'rejetée')
    <p>Motif du rejet (chef): {{ $demande->motif_rejet_chef }}</p>
@endif
@if($demande->etat_directeur === 'rejetée')
    <p>Motif du rejet (directeur): {{ $demande->motif_rejet_directeur }}</p>
@endif
    <!-- Ajoute d’autres infos si besoin -->
</body>
</html>
