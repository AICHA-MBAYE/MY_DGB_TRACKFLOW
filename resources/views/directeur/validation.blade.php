@extends('layouts.app')

@section('title', 'Validation absence')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Validation des demandes d'absence</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Directeur</p>
@endsection

@section('contenu')
<div class="container mx-auto p-4">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Succès !</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($demandes->isEmpty())
        <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
            <p class="font-bold">Aucune demande en attente</p>
            <p class="text-sm">Il n'y a actuellement aucune demande d'absence en attente de validation de votre part.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($demandes as $demande)
                <div class="bg-white rounded-lg shadow-md overflow-hidden p-6">
                    <h2 class="text-xl font-semibold mb-3 text-gray-700">Demande #{{ $demande->id }}</h2>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Agent :</span> {{ $demande->user->name ?? 'N/A' }} {{-- Assurez-vous que l'utilisateur est lié --}}
                    </p>

                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Date de début :</span> {{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}
                    </p>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Date de fin :</span> {{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}
                    </p>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Motif :</span> {{ $demande->motif }}
                    </p>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Justificatif :</span>
                        @if ($demande->justificatif)
                            <a href="{{ asset('storage/' . $demande->justificatif) }}" target="_blank" class="text-blue-500 hover:underline">
                                Voir le justificatif
                            </a>
                        @else
                            Aucun
                        @endif
                    </p>
                    <p class="text-gray-600 mb-4">
                        <span class="font-medium">Statut Chef :</span>
                        @if ($demande->etat_chef == 'acceptée')
                            <span class="text-green-600 font-bold">Validée</span>
                        @elseif ($demande->etat_chef == 'rejetée')
                            <span class="text-red-600 font-bold">Rejetée</span>
                        @else
                            <span class="text-yellow-600 font-bold">En attente</span>
                        @endif
                    </p>

                    <form method="POST" action="{{ route('directeur.traiter', $demande->id) }}">
    @csrf
    <select name="action" id="action" onchange="toggleMotif(this)">
        <option value="acceptée">Accepter</option>
        <option value="rejetée">Rejeter</option>
    </select>
    <div id="motif_rejet" style="display:none;">
        <label>Motif du rejet :</label>
        <select name="motif_rejet_directeur">
            <option value="justificatif éronée">Justificatif éronée</option>
            <option value="formulaire incomplet">Formulaire incomplet</option>
            <option value="rejet definitif">Rejet définitif</option>
        </select>
    </div>
    <button type="submit">Valider</button>
</form>

<script>
function toggleMotif(select) {
    document.getElementById('motif_rejet').style.display = select.value === 'rejetée' ? 'block' : 'none';
}
</script>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
