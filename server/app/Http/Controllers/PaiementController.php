<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Exception;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $payement = Paiement::all();
        return response()->json([
            'status_code' => 200,
            'status_message' => 'Les paiements ont été récupérées avec succès',
            'items' => $payement
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Paiement $paiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paiement $paiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paiement $paiement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $paiement =Paiement::findOrfail($id);
            $paiement->delete();
            return response()->json([
            'status_code' => 200,
            'status_message' => 'Le paiement désiré est supprimée avec succès'
            ]);

        }catch(Exception $e){
            return response()->json($e);
        }
    }
}

