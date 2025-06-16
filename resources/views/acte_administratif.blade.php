<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Acte Administratif</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            color: #222;
            margin: 40px;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #003366;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .header img {
            height: 80px;
            margin-right: 20px;
        }
        .header-title {
            font-size: 2rem;
            color: #003366;
            font-weight: bold;
        }
        .section-title {
            color: #003366;
            font-size: 1.2rem;
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-table td {
            padding: 8px 12px;
        }
        .info-table tr:nth-child(even) {
            background: #f4f6f9;
        }
        .status {
            font-weight: bold;
            color: {{ $demande->etat_directeur === 'acceptée' ? '#006400' : '#cc0000' }};
        }
        .motif {
            color: #cc0000;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/dgb-logo.png') }}" alt="Logo DGB">
        <div class="header-title">DIRECTION GÉNÉRALE DU BUDGET<br>Acte Administratif</div>
    </div>

    <div>
        <div class="section-title">Informations de l’agent</div>
        <table class="info-table">
            <tr>
                <td><strong>Prénom :</strong></td>
                <td>{{ $agent ? $agent->prenom : 'Non défini' }}</td>
            </tr>
            <tr>
                <td><strong>Nom :</strong></td>
                <td>{{ $agent ? $agent->nom : 'Non défini' }}</td>
            </tr>
            <tr>
                <td><strong>Direction :</strong></td>
                <td>{{ $agent && $agent->direction ? $agent->direction : 'Non défini' }}</td>
            </tr>
        </table>

        <div class="section-title">Détails de la demande d’absence</div>
        <table class="info-table">
            <tr>
                <td><strong>Date début :</strong></td>
                <td>{{ $demande->date_debut }}</td>
            </tr>
            <tr>
                <td><strong>Date fin :</strong></td>
                <td>{{ $demande->date_fin }}</td>
            </tr>
            <tr>
                <td><strong>Motif :</strong></td>
                <td>{{ $demande->motif }}</td>
            </tr>
            <tr>
                <td><strong>Statut :</strong></td>
                <td class="status">{{ ucfirst($demande->etat_directeur) }}</td>
            </tr>
            @if($demande->etat_directeur === 'rejetée' && $demande->motif_rejet_directeur)
            <tr>
                <td><strong>Motif du rejet :</strong></td>
                <td class="motif">{{ $demande->motif_rejet_directeur }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div style="margin-top: 40px; text-align: right;">
        <span>Fait à Dakar, le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
        <br>
        <span>Le Directeur</span>
    </div>
</body>
</html>
