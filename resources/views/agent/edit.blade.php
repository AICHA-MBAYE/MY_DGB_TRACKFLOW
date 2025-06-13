@extends('layouts.app')
@section('title', 'Modification')

@section('titreContenu')
    <h1 class="text-center text-3xl font-bold" style="color: rgb(34, 34,155);">Modifier un agent</h1>
@endsection

@section('sousTitreContenu')
    <p class="text-center text-lg text-black">Modifiez les informations ci-dessous</p>
@endsection

@section('contenu')
    <form action="{{ route('agent.update', compact('agent')) }}" method="POST">
        @csrf
        @method('put')
        <div class="container-fluid">
            <div class="row">
                <div class="md:col-span-2" style="color:black";>
                    <label for="prenom">Prénom</label>
                    <input value="{{ old('prenom') ?? $agent->prenom }}" required="required"
                        class="form-control @error('prenom') is-invalid
@enderror" type="text" name="prenom"
                        id="prenom">
                    @error('prenom')
                        <span class="text-danger b">{{ $errors->first('prenom') }}
                        </span>
                    @enderror
                </div>
                <div class="md:col-span-2" style="color:black";>
                    <label for="nom">Nom</label>
                    <input value="{{ old('nom') ?? $agent->nom }}" required="
required"
                        class="form-control @error('nom') is-invalid @enderror" type="
text" name="nom"
                        id="nom">
                    @error('nom')
                        <span class="text-danger b">{{ $errors->first('nom') }}< /span>
                            @enderror
                </div>
                <div class="md:col-span-2" style="color:black";>
                    <label for="cni">Email</label>
                    <input value="{{ old('email') ?? $agent->email }}" required="
required"
                        class="form-control @error('email') is-invalid @enderror" type="
text" name="email"
                        id="email">
                    @error('email')
                        <span class="text-danger b">{{ $errors->first('email') }}< /span>
                            @enderror
                </div>
                <div class="md:col-span-2" style="color:black;">
                    <label for="role">Rôle</label>
                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">-- Choisissez un rôle --</option>
                        <option value="super_admin" {{ old('role', $agent->role) === 'super_admin' ? 'selected' : '' }}>Super-administrateur</option>
                        <option value="admin_sectoriel" {{ old('role', $agent->role) === 'admin_sectoriel' ? 'selected' : '' }}>Administrateur sectoriel</option>
                        <option value="directeur" {{ old('role', $agent->role) === 'directeur' ? 'selected' : '' }}>Directeur</option>
                        <option value="chef_service" {{ old('role', $agent->role) === 'chef_service' ? 'selected' : '' }}>Chef de service</option>
                        <option value="agent" {{ old('role', $agent->role) === 'agent' ? 'selected' : '' }}>Agent</option>
                    </select>
                    @error('role')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="md:col-span-2" style="color:black;">
                    <label for="direction">Direction</label>
                    <select name="direction" id="direction" class="form-control @error('direction') is-invalid @enderror" required>
                        <option value="">-- Sélectionnez une direction --</option>
                        <option value="DAP" {{ old('direction') == 'DAP' ? 'selected' : '' }}>Direction de l'Administration et du Personnel (DAP)</option>
                        <option value="DCI" {{ old('direction') == 'DCI' ? 'selected' : '' }}>Direction du Contrôle Interne (DCI)</option>
                        <option value="DSI" {{ old('direction') == 'DSI' ? 'selected' : '' }}>Direction des Systèmes d'Information (DSI)</option>
                        <option value="DPB" {{ old('direction') == 'DPB' ? 'selected' : '' }}>Direction de la Programmation Budgétaire (DPB)</option>
                        <option value="CSS" {{ old('direction') == 'CSS' ? 'selected' : '' }}>Cellule de Suivi et de Synthèse(CSS)</option>
                        <option value="CER" {{ old('direction') == 'CER' ? 'selected' : '' }}>Cellule des Etudes et de la Réglementation(CER)</option>
                    </select>
                    @error('direction')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-12 mt-1">
                    <button type="submit" class="btn btn-success float-right">
                        Enregistrer</button>
                </div>
            </div>
        </div>
    </form>
@endsection
