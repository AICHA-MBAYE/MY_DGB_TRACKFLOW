{{-- filepath: resources/views/directeur/historique.blade.php --}}
@extends('layouts.app')

@section('contenu')
<div style="max-width:900px;margin:30px auto;">
    <h2 style="text-align:center;margin-bottom:25px;">Historique des validations du Directeur</h2>
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;background:#fff;box-shadow:0 2px 8px #eee;">
            <thead style="background:#033568;color:#fff;">
                <tr>
                    <th style="padding:10px;">Agent</th>
                    <th style="padding:10px;">Date/Heure de traitement</th>
                    <th style="padding:10px;">Action</th>

                </tr>
            </thead>
            <tbody>
                @forelse($historique as $item)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:10px;">
                        {{ $item->demande->agent->prenom ?? '' }} {{ $item->demande->agent->nom ?? '' }}
                    </td>
                    <td style="padding:10px;">{{ \Carbon\Carbon::parse($item->validated_at)->format('d/m/Y H:i') }}</td>
                    <td style="padding:10px;">
                        @if($item->action === 'acceptée')
                            <span style="color:green;font-weight:bold;">Acceptée</span>
                        @elseif($item->action === 'rejetée')
                            <span style="color:#c00;font-weight:bold;">Rejetée</span>
                        @else
                            <span>{{ ucfirst($item->action) }}</span>
                        @endif
                         @if($item->demande)
                        <a href="{{ route('demande_absence.show', $item->demande->id) }}"
                           style="padding:6px 12px;background:#00509e;color:#fff;border-radius:4px;text-decoration:none;">
                            Voir détails
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:30px;text-align:center;color:#888;font-size:1.1em;">
                        <i class="fa fa-info-circle" style="font-size:1.5em;color:#00509e;"></i><br>
                        Aucune demande traitée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
