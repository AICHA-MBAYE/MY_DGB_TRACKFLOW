@extends('layouts.app')

@section('title', 'Validation absence')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34, 155)">Validation des demandes d'absence</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Chef de Service</p>
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
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow mx-auto">
                <thead class="bg-blue-900 text-white">
                    <tr>
                        <th class="px-4 py-2" style="background-color:#003366; color:#fff;" >Agent</th>
                        <th class="px-4 py-2" style="background-color:#003366; color:#fff;" >Date début</th>
                        <th class="px-4 py-2" style="background-color:#003366; color:#fff;" >Date fin</th>
                        <th class="px-4 py-2" style="background-color:#003366; color:#fff;" >Motif</th>
                        <th class="px-4 py-2" style="background-color:#003366; color:#fff;" >Direction</th>
                        <th class="px-4 py-2" style="background-color:#003366; color:#fff;" >Justificatif</th>
                        <th class="px-4 py-2" style="background-color:#003366; color:#fff;" >Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($demandes as $demande)
                   <tr>
                            <td class="border px-4 py-2">{{ $demande->agent->prenom ?? '' }} {{ $demande->agent->nom ?? '' }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($demande->date_debut)->format('d/m/Y') }}</td>
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($demande->date_fin)->format('d/m/Y') }}</td>
                            <td class="border px-4 py-2">{{ $demande->motif }}</td>
                            <td class="border px-4 py-2">{{ $demande->agent->direction ?? '-' }}</td>
                            <td class="border px-4 py-2">
                                @if ($demande->justificatif)
                                    <a href="{{ asset('storage/' . $demande->justificatif) }}" target="_blank" class="text-blue-500 hover:underline">
                                        Voir
                                    </a>
                                @else
                                    Aucun
                                @endif
                            </td>
                            <td class="border px-4 py-2">
                                <form method="POST" action="{{ route('chef.traiter', $demande->id) }}">
                                    @csrf
                                    <select name="action" onchange="toggleMotif(this, {{ $demande->id }})">
                                        <option value="acceptée">Accepter</option>
                                        <option value="rejetée">Rejeter</option>
                                    </select>
                                    <div id="motif_rejet_{{ $demande->id }}" style="display:none; margin-top:5px;">
                                        <label>Motif du rejet :</label>
                                        <select name="motif_rejet_chef">
                                            <option value="justificatif éronée">Justificatif éronée</option>
                                            <option value="formulaire incomplet">Formulaire incomplet</option>
                                            <option value="rejet definitif">Rejet définitif</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2">Valider</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <script>
            function toggleMotif(select, id) {
                document.getElementById('motif_rejet_' + id).style.display = select.value === 'rejetée' ? 'block' : 'none';
            }
        </script>
    @endif
</div>
@endsection
