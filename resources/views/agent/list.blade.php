@extends('layouts.app')

@section('title', 'Liste des agents')

@section('titreContenu')
    <span style="color: black;">Agents</span>
@endsection

@section('sousTitreContenu')
    <span style="color: black;">Liste des agents</span>
@endsection


@section('contenu')
<div class="bg-white p-6 rounded shadow-md max-w-7xl mx-auto">
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded shadow">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Prénom</th>
                    <th class="px-4 py-2 border">Nom</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Rôle</th>
                    <th class="px-4 py-2 border w-1/5">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agents as $agent)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->id }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->prenom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->nom }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ $agent->email }}</td>
                        <td class="px-4 py-2 border" style="color: black;">{{ ucfirst(str_replace('_', ' ', $agent->role)) }}</td>
                        <td class="px-4 py-2 border">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('agent.edit', $agent) }}" class="btn btn-primary">
                                    Modifier
                                </a>

                                <form action="{{ route('agent.destroy', $agent) }}" method="POST"
                                      onsubmit="return confirm('Voulez-vous vraiment supprimer cet agent ?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Aucun agent trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('agent.create') }}" class="btn btn-primary">
            Ajouter un agent
        </a>
    </div>
</div>
@endsection
