@extends('base')
@section('title','Affichage agent')
@section('titreContenu','Agents')
@section('sousTitreContenu','Détails agent')
@section('contenu')
<div class="container">
<div class="row">
<div class="col-12">
<a class="btn btn-secondary mb-2" href="{{route('agent.
index')}}" role="button">
<i class="fa fa-reply" aria-hidden="true"></i>
</a>
<a class="btn btn-warning mb-2" href="{{route('agent.edit',
compact('agent'))}}" role="button">
<i class="fa fa-edit" aria-hidden="true"></i>
</a>
</div>
<div class="col-12">
<table class="table table-responsive-md table-bordered">
<tbody>
<tr>
<th>Prénom</th>
<td>{{$agent->prenom}}</td>
<th>Nom</th>
<td>{{$agent->nom}}</td>
</tr>
<tr>
<th>Email</th>
<td>{{$agent->email}}</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
@endsection