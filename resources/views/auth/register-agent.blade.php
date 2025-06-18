@extends('layouts.app')

{{-- Le titre de la page affiché dans le navigateur --}}
@section('title', 'Inscription Agent')

{{-- Titre principal du contenu affiché sur la page --}}
@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Inscription d'un nouvel agent</h1>
@endsection

{{-- Sous-titre du contenu --}}
@section('sousTitreContenu')
    <p class="text-center text-xl text-black">Remplissez le formulaire d'inscription ci-dessous</p>
@endsection


{{-- Contenu principal du formulaire d'inscription --}}
@section('contenu')
<div class="bg-white p-6 rounded shadow-md max-w-3xl mx-auto">
    {{-- Formulaire d'inscription --}}
    {{-- L'action du formulaire pointe vers la route 'agent.store' pour gérer l'enregistrement --}}
    <form action="{{ route('agent.store') }}" method="POST" novalidate>
        @csrf {{-- Protection CSRF obligatoire pour les formulaires Laravel --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Champ Prénom -->
            <div>
                <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom</label>
                <input
                    id="prenom"
                    name="prenom"
                    type="text"
                    required
                    value="{{ old('prenom') }}" {{-- old() maintient la valeur en cas d'erreur de validation --}}
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('prenom') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('prenom') {{-- Affiche les erreurs de validation pour le champ 'prenom' --}}
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
                    value="{{ old('nom') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('nom') border-red-600 @enderror"
                    style="color: black;"
                >
                @error('nom')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Champ Email (prend toute la largeur sur les écrans moyens) -->
            <div class="md:col-span-2">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    required
                    value="{{ old('email') }}"
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
                    <option value="DAP" {{ old('direction') == 'DAP' ? 'selected' : '' }}>Direction de l'Administration et du Personnel (DAP)</option>
                    <option value="DCI" {{ old('direction') == 'DCI' ? 'selected' : '' }}>Direction du Contrôle Interne (DCI)</option>
                    <option value="DSI" {{ old('direction') == 'DSI' ? 'selected' : '' }}>Direction des Systèmes d'Information (DSI)</option>
                    <option value="DPB" {{ old('direction') == 'DPB' ? 'selected' : '' }}>Direction de la Programmation Budgétaire (DPB)</option>
                    <option value="DCB" {{ old('direction') == 'DCB' ? 'selected' : '' }}>Direction du Contrôle Budgétaire (DCB)</option>
                    <option value="DODP" {{ old('direction') == 'DODP' ? 'selected' : '' }}>Direction de l'Ordonnancement des Dépenses Publiques (DODP)</option>
                    <option value="DS" {{ old('direction') == 'DS' ? 'selected' : '' }}>Direction de la Solde (DS)</option>
                    <option value="DP" {{ old('direction') == 'DP' ? 'selected' : '' }}>Direction des Pensions (DP)</option>
                    <option value="DMTA" {{ old('direction') == 'DMTA' ? 'selected' : '' }}>Direction du Matériel et du Transit Administratif (DMTA)</option>
                    <option value="CSS" {{ old('direction') == 'CSS' ? 'selected' : '' }}>Cellule de Suivi et de Synthèse (CSS)</option>
                    <option value="CER" {{ old('direction') == 'CER' ? 'selected' : '' }}>Cellule des Études et de la Réglementation (CER)</option>
                </select>
                @error('direction')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Liste déroulante Division (initialement vide ou cachée, remplie par JS) -->
            <div class="md:col-span-2">
                <label for="division" class="block text-gray-700 font-semibold mb-2">Division</label>
                <select
                    id="division"
                    name="division"
                    required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-600 @error('division') border-red-600 @enderror"
                    style="color: black;"
                    disabled {{-- Désactivé par défaut jusqu'à ce qu'une direction soit choisie --}}
                >
                    <option value="">-- Sélectionnez une division --</option>
                </select>
                @error('division')
                    <p class="text-red-600 mt-1 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Bouton d'inscription --}}
        <div class="mt-6 text-right">
            <button
                type="submit"
                class="bg-blue-800 text-white px-6 py-2 rounded hover:bg-blue-900 transition"
            >
                S'inscrire
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
            "DPB": { // Direction de la Programmation Budgétaire
                "DS": "Division de la synthèse",
                "DSE": "Division des secteurs économiques",
                "DSSOC": "Division des secteurs sociaux",
                "DSSOUV": "Division des secteurs de souveraineté",
                "CI": "Cellule informatique",
                "BAF": "Bureau Administratif et Financier"
            },
            "DCB": { // Direction du Contrôle Budgétaire
                "DS": "Division de la Synthèse",
                "DCR": "Division du Contrôle de Régularité",
                "DCP": "Division du Contrôle de Performance",
                "CI": "Cellule informatique", // <-- Correction: ajout de la virgule
                "BAF": "Bureau Administratif et Financier"
            },
            "DSI": { // Direction des Systèmes d'Information
                "DED": "Division des Études et du Développement",
                "DEM": "Division de l'Exploitation et de la Maintenance",
                "DCQRU": "Division du Contrôle Qualité et des Relations avec les Usagers",
                "DPI": "Division des Projets innovants",
                "BAF": "Bureau Administratif et Financier"
            },
            "DCI": { // Direction du Contrôle Interne
                "DSD": "Division de la Synthèse et de la Documentation",
                "DACG": "Division de l'Audit et du Contrôle de Gestion",
                "DSP": "Division du Suivi de la Performance",
                "BAF": "Bureau Administratif et Financier"
            },
            "DAP": { // Direction de l'Administration et du Personnel
                "DGRH": "Division de la Gestion des Ressources Humaines (recrutement, carrières, formation)",
                "DMG": "Division des Moyens Généraux (logistique, maintenance des locaux)",
                "DAD": "Division des Archives et de la Documentation",
                "BAF": "Bureau Administratif et Financier"
            },
            "DODP": { // Direction de l'Ordonnancement des Dépenses Publiques
                "DODPER": "Division de l'Ordonnancement des Dépenses de Personnel",
                "DODFI": "Division de l'Ordonnancement des Dépenses de Fonctionnement et d'Investissement",
                "DSCE": "Division du Suivi et du Contrôle des Engagements",
                "BAF": "Bureau Administratif et Financier"
            },
            "DS": { // Direction de la Solde
                "DGCP": "Division de la Gestion des Carrières et des Positions",
                "DTR": "Division du Traitement des Rémunérations",
                "DRR": "Division des Recouvrements et des Retenues",
                "BAF": "Bureau Administratif et Financier"
            },
            "DP": { // Direction des Pensions
                "DPC": "Division des Pensions Civiles",
                "DPM": "Division des Pensions Militaires",
                "DRAD": "Division des Rentes et Allocations Diverses",
                "BAF": "Bureau Administratif et Financier"
            },
            "DMTA": { // Direction du Matériel et du Transit Administratif
                "DAA": "Division des Achats et Approvisionnements", // Correction: Utilisez les abréviations que vous avez données
                "DSD": "Division du Stockage et de la Distribution", // Correction: Utilisez les abréviations que vous avez données
                "DTP": "Division du Transit Administratif et du Parc Automobile", // Correction: Utilisez les abréviations que vous avez données
                "BAF": "Bureau Administratif et Financier"
            },
            "CSS": { // Cellule de Suivi et de Synthèse
                "SCAD": "Section de Collecte et d'Analyse des Données",
                "SRNRS": "Section de Rédaction des Notes et Rapports de Synthèse",
                "SSRD": "Section de Suivi des Recommandations et Décisions", // <-- Correction: ajout de la virgule
                "BAF": "Bureau Administratif et Financier"
            },
            "CER": { // Cellule des Études et de la Réglementation
                "SEEF": "Section des Études Économiques et Financières",
                "SEMJR": "Section de l'Élaboration et de la Mise à Jour Réglementaire",
                "SEVJI": "Section de Veille Juridique et Institutionnelle", // <-- Correction: ajout de la virgule
                "BAF": "Bureau Administratif et Financier"
            }
        };

        function populateDivisions() {
            const selectedDirection = directionSelect.value;
            divisionSelect.innerHTML = '<option value="">-- Sélectionnez une division --</option>'; // Réinitialise les options

            if (selectedDirection && DIRECTIONS_AND_DIVISIONS[selectedDirection]) {
                const divisions = DIRECTIONS_AND_DIVISIONS[selectedDirection];
                for (const value in divisions) { // <-- Correction: 'coernst' remplacé par 'const'
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = divisions[value];
                    divisionSelect.appendChild(option);
                }
                divisionSelect.disabled = false; // Active le champ division
            } else {
                divisionSelect.disabled = true; // Désactive si aucune direction valide n'est sélectionnée
            }

            // Pour maintenir la sélection en cas d'erreur de validation
            const oldDivision = "{{ old('division') }}";
            if (oldDivision) {
                divisionSelect.value = oldDivision;
            }
        }

        // Écoute les changements sur la sélection de la direction
        directionSelect.addEventListener('change', populateDivisions);

        // Appelle la fonction une fois au chargement de la page si une direction est déjà sélectionnée (ex: après une erreur de validation)
        populateDivisions();
    });
</script>
@endsection
