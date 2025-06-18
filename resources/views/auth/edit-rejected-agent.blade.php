@extends('layouts.app')

@section('title', 'Modifier Inscription Rejetée')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Modifier votre inscription</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-xl text-black">Veuillez corriger les informations de votre inscription ci-dessous et soumettre à nouveau.</p>
@endsection

@section('contenu')
<div class="bg-white p-6 rounded shadow-md max-w-3xl mx-auto">
    <form action="{{ route('agent.update_rejected_registration', $agent->id) }}" method="POST" novalidate>
        @csrf
        @method('POST')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Champ Prénom -->
            <div>
                <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
                <input
                    id="prenom"
                    name="prenom"
                    type="text"
                    required
                    value="{{ old('prenom', $agent->prenom) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('prenom') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('prenom')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Champ Nom -->
            <div>
                <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom</label>
                <input
                    id="nom"
                    name="nom"
                    type="text"
                    required
                    value="{{ old('nom', $agent->nom) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('nom') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('nom')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Champ Email -->
            <div class="md:col-span-2">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                    value="{{ old('email', $agent->email) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('email') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('email')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Liste déroulante Direction -->
            <div class="md:col-span-2">
                <label for="direction" class="block text-gray-700 font-semibold mb-2">Direction</label>
                <select
                    id="direction"
                    name="direction"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('direction') border-red-600 @enderror"
                    style="color: black;">
                    <option value="">-- Sélectionnez une direction --</option>
                    <option value="DAP" {{ old('direction', $agent->direction) == 'DAP' ? 'selected' : '' }}>Direction de l'Administration et du Personnel (DAP)</option>
                    <option value="DCI" {{ old('direction', $agent->direction) == 'DCI' ? 'selected' : '' }}>Direction du Contrôle Interne (DCI)</option>
                    <option value="DSI" {{ old('direction', $agent->direction) == 'DSI' ? 'selected' : '' }}>Direction des Systèmes d'Information (DSI)</option>
                    <option value="DPB" {{ old('direction', $agent->direction) == 'DPB' ? 'selected' : '' }}>Direction de la Programmation Budgétaire (DPB)</option>
                    <option value="DCB" {{ old('direction', $agent->direction) == 'DCB' ? 'selected' : '' }}>Direction du Contrôle Budgétaire (DCB)</option>
                    <option value="DODP" {{ old('direction', $agent->direction) == 'DODP' ? 'selected' : '' }}>Direction de l'Ordonnancement des Dépenses Publiques (DODP)</option>
                    <option value="DS" {{ old('direction', $agent->direction) == 'DS' ? 'selected' : '' }}>Direction de la Solde (DS)</option>
                    <option value="DP" {{ old('direction', $agent->direction) == 'DP' ? 'selected' : '' }}>Direction des Pensions (DP)</option>
                    <option value="DMTA" {{ old('direction', $agent->direction) == 'DMTA' ? 'selected' : '' }}>Direction du Matériel et du Transit Administratif (DMTA)</option>
                    <option value="CSS" {{ old('direction', $agent->direction) == 'CSS' ? 'selected' : '' }}>Cellule de Suivi et de Synthèse (CSS)</option>
                    <option value="CER" {{ old('direction', $agent->direction) == 'CER' ? 'selected' : '' }}>Cellule des Études et de la Réglementation (CER)</option>
                </select>
                @error('direction')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Liste déroulante Division (remplie dynamiquement par JS) -->
            <div class="md:col-span-2">
                <label for="division" class="block text-gray-700 font-semibold mb-2">Division</label>
                <select
                    id="division"
                    name="division"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('division') border-red-600 @enderror"
                    style="color: black;"
                >
                    <option value="">-- Sélectionnez une division --</option>
                </select>
                @error('division')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 text-right">
            <button
                type="submit"
                class="bg-blue-800 text-white px-6 py-2 rounded hover:bg-blue-900 transition"
            >
                Soumettre les modifications
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const directionSelect = document.getElementById('direction');
        const divisionSelect = document.getElementById('division');

        // Mappage des directions aux divisions
        const DIRECTIONS_AND_DIVISIONS = {
            "DPB": {
                "DS": "Division de la synthèse",
                "DSE": "Division des secteurs économiques",
                "DSSOC": "Division des secteurs sociaux",
                "DSSOUV": "Division des secteurs de souveraineté",
                "CI": "Cellule informatique",
                "BAF": "Bureau Administratif et Financier"
            },
            "DCB": {
                "DS": "Division de la Synthèse",
                "DCR": "Division du Contrôle de Régularité",
                "DCP": "Division du Contrôle de Performance",
                "CI": "Cellule informatique",
                "BAF": "Bureau Administratif et Financier"
            },
            "DSI": {
                "DED": "Division des Études et du Développement",
                "DEM": "Division de l'Exploitation et de la Maintenance",
                "DCQRU": "Division du Contrôle Qualité et des Relations avec les Usagers",
                "DPI": "Division des Projets innovants",
                "BAF": "Bureau Administratif et Financier"
            },
            "DCI": {
                "DSD": "Division de la Synthèse et de la Documentation",
                "DACG": "Division de l'Audit et du Contrôle de Gestion",
                "DSP": "Division du Suivi de la Performance",
                "BAF": "Bureau Administratif et Financier"
            },
            "DAP": {
                "DGRH": "Division de la Gestion des Ressources Humaines (recrutement, carrières, formation)",
                "DMG": "Division des Moyens Généraux (logistique, maintenance des locaux)",
                "DAD": "Division des Archives et de la Documentation",
                "BAF": "Bureau Administratif et Financier"
            },
            "DODP": {
                "DODPER": "Division de l'Ordonnancement des Dépenses de Personnel",
                "DODFI": "Division de l'Ordonnancement des Dépenses de Fonctionnement et d'Investissement",
                "DSCE": "Division du Suivi et du Contrôle des Engagements",
                "BAF": "Bureau Administratif et Financier"
            },
            "DS": {
                "DGCP": "Division de la Gestion des Carrières et des Positions",
                "DTR": "Division du Traitement des Rémunérations",
                "DRR": "Division des Recouvrements et des Retenues",
                "BAF": "Bureau Administratif et Financier"
            },
            "DP": {
                "DPC": "Division des Pensions Civiles",
                "DPM": "Division des Pensions Militaires",
                "DRAD": "Division des Rentes et Allocations Diverses",
                "BAF": "Bureau Administratif et Financier"
            },
            "DMTA": {
                "DAA": "Division des Achats et Approvisionnements",
                "DSD": "Division du Stockage et de la Distribution",
                "DTP": "Division du Transit Administratif et du Parc Automobile",
                "BAF": "Bureau Administratif et Financier"
            },
            "CSS": {
                "SCAD": "Section de Collecte et d'Analyse des Données",
                "SRNRS": "Section de Rédaction des Notes et Rapports de Synthèse",
                "SSRD": "Section de Suivi des Recommandations et Décisions",
                "BAF": "Bureau Administratif et Financier"
            },
            "CER": {
                "SEEF": "Section des Études Économiques et Financières",
                "SEMJR": "Section de l'Élaboration et de la Mise à Jour Réglementaire",
                "SEVJI": "Section de Veille Juridique et Institutionnelle",
                "BAF": "Bureau Administratif et Financier"
            }
        };

        function populateDivisions() {
            const selectedDirection = directionSelect.value;
            divisionSelect.innerHTML = '<option value="">-- Sélectionnez une division --</option>';

            if (selectedDirection && DIRECTIONS_AND_DIVISIONS[selectedDirection]) {
                const divisions = DIRECTIONS_AND_DIVISIONS[selectedDirection];
                for (const value in divisions) {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = divisions[value];
                    divisionSelect.appendChild(option);
                }
                divisionSelect.disabled = false;
            } else {
                divisionSelect.disabled = true;
            }

            // Pour maintenir la sélection en cas d'erreur de validation ou lors de l'édition
            const oldDivision = "{{ old('division', $agent->division) }}";
            if (oldDivision) {
                divisionSelect.value = oldDivision;
            }
        }

        directionSelect.addEventListener('change', populateDivisions);
        populateDivisions();
    });
</script>
@endsection