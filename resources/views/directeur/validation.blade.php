
@extends('layouts.app')

@section('contenu')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Validation des demandes d'absence (Directeur)</h1>

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
                        @if ($demande->etat_chef == 'valide')
                            <span class="text-green-600 font-bold">Validée</span>
                        @elseif ($demande->etat_chef == 'rejete')
                            <span class="text-red-600 font-bold">Rejetée</span>
                        @else
                            <span class="text-yellow-600 font-bold">En attente</span>
                        @endif
                    </p>

                    <form action="{{ route('directeur.traiter', $demande->id) }}" method="POST" class="flex space-x-3">
                        @csrf
                        <button type="submit" name="action" value="valide"
                                class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                            Valider
                        </button>
                        <button type="submit" name="action" value="rejete"
                                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                            Rejeter
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
