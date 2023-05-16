<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
   
    //affichage des informations
    public function show($id)
    {
        $enseignant = Enseignant::findOrFail($id);
    
        return view('enseignants.show', compact('enseignant'));
    }
    
    // suppression d'un enseignant
    public function destroy($id)
    {
        $enseignant = Enseignant::findOrFail($id);
        $enseignant->delete();
    
        // Redirection vers une autre page ou retour d'une réponse JSON si vous utilisez une API
    }

    //Create un enseignant
    public function store(){
        //Validate data coming from user
    
        /* $fields = $request->validate([
            'name' => 'required | string',
            'email' => 'required | string |unique:users,email',
            'password' => 'required | string |confirmed',
            'type' => 'required | string'
        ]); */
    }
    
      }


