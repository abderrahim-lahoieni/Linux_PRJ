<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGradeRequest;
use Exception;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Les grades ont été récupérées avec succès',
                'items' => Grade::all()
            ]);
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGradeRequest $request)
    {
        $grade = new Grade();
        
        $grade->designation = $request->designation;
        $grade->charge_statutaire = $request->charge_statutaire;
        $grade->taux_horaire_vacation = $request->taux_horaire_vacation;

        $grade->save();

        return response()->json([
            'status_code' => 201,
            'status_message' => 'Le grade est ajouté avec succès',
            'data' => $grade
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id){
       
        try{
            $grade =Grade::find($id);

            return response()->json([
                'status_code' => 201,
                'status_message' => 'Le grade est bien trouvée avec succès',
                'data' => $grade
            ]);
        }catch(Exception $e){
                return response()->json($e);
        }
        
    }
        

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {   
        try {

            //Chercher l'enregistrement avec id passée par l'utilisateur
            $grade = Grade::find($id);

            $grade->designation = $request->designation;
            $grade->charge_statutaire = $request->charge_statutaire;
            $grade->taux_horaire_vacation = $request->taux_horaire_vacation;

            $grade->save(); //Enregistrement de nouvelles valeurs

            return response()->json([
                'status_code' => 201,
                'status_message' => 'Le grade est edité avec succès',
                'data' => $grade
            ]);

        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $grade=Grade::find($id);
            $grade->delete();
            return response()->json([
            'status_code' => 200,
            'status_message' => 'L etablissement est supprimée avec succès',
            'data' => $grade
            ]);
        }catch(Exception $e){
            return response()->json($e);
        }
    }
}
