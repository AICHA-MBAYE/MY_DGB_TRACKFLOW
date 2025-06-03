<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
     use ValidatesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //récupération de la liste des agents
        $agents = Agent::all();
        //retourner la page agent/list.blade.php avec la liste des agents
        return view('agent.list' ,compact('agents'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agent.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //verification de la validité des champs
        $this->validate($request,[
                    'prenom'=>'required',
                    'nom'=>'required',
                    'email' => 'required|email|unique:agents,email',
                    'role' => 'required|in:super_admin,admin_sectoriel,directeur,chef_service,agent'


]);

        //création de l'objet
        Agent::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        //redirection vers la liste des agents
        return redirect()->route('agent.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Agent $agent)
    {
        return view('agent.show' ,compact('agent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agent $agent)
    {
        return view('agent.edit' ,compact('agent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agent $agent)
    {
        // verification de la validité des champs
         $this->validate($request, [
        'prenom' => 'required',
        'nom' => 'required',
        'email' => ['required', 'email', Rule::unique('agents')->ignore($agent->id)],
        'role' => ['required', Rule::in(['super_admin', 'admin_sectoriel', 'directeur', 'chef_service', 'agent'])],
    ]);
        // création de l'objet
        $agent->update([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        //redirection vers la liste des agents
        return redirect()->route('agent.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('agent.index');
    }
}
