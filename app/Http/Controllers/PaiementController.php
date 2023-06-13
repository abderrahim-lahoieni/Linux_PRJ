<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Grade;
use App\Models\Paiement;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    public function Afichage_Mon_Payement($Annee)
    {
        //$payement = Paiement::all();
        // return response()->json([
        //     'status_code' => 200,
        //     'status_message' => 'Les paiements ont été récupérées avec succès',
        //     'items' => $payement
        // ]);
        if (Gate::allows('role_enseignant', Auth::user())) {

            $id_user = Auth::id();
            $Enseignant = Enseignant::where('id_user', $id_user)->first();

            $paiement = Paiement::where('id_intervenant', $Enseignant->id)
                ->where('annee_univ', $Annee)
                ->get(['vh', 'taux_h', 'brut', 'ir', 'net', 'annee_univ', 'semestre']);

            return response()->json([
                'paiement' => $paiement
            ]);
        } else {
            abort('403');
        }
    }


    //Afficher le total net qui va etre dans la banque de l'enseignant
    public function Afficher_salaire_Total($Annee)
    {

        if (Gate::allows('role_enseignant', Auth::user())) {

            $user_id = Auth::id();
            $Ens = Enseignant::where('id_user', $user_id)->first();
            $salaire = DB::select('SELECT calculate_salaire_totalnet(?, ?)', [$Ens->id, $Annee]);

            return response()->json([
                'status_code' => 200,
                'salaire_Total_net' => $salaire
            ]);

        }

    }

    //Afficher le total des salaires supplémentaire qui va etre afficher à l'enseignant (Ne va pas etre dans la banque)
    public function Afficher_Salaire_Sup($Annee)
    {
        if (Gate::allows('role_enseignant', Auth::user())) {

            $user_id = Auth::id();
            $Ens = Enseignant::where('id_user', $user_id)->first();
            $salaire = DB::select('SELECT calculate_salaire_sup(?, ?)', [$Ens->id, $Annee]);

            return response()->json([
                'status_code' => 200,
                'salaire_Total_supplémentaire' => $salaire
            ]);

        }
    }

    //Afficher le total des salaires vacataires qui va etre afficher à l'enseignant (Ne va pas etre dans la banque)
    public function Afficher_Salaire_Vacataire($Annee)
    {
        if (Gate::allows('role_enseignant', Auth::user())) {

            $user_id = Auth::id();
            $Ens = Enseignant::where('id_user', $user_id)->first();
            $salaire = DB::select('SELECT calculate_salaire_vacation(?, ?)', [$Ens->id, $Annee]);

            return response()->json([

                'status_code' => 200,
                'salaire_Total_Vacataire' => $salaire

            ]);

        }
    }
    // public function Calcule_Mon_Salaire($Annee)
    // {
    //     if (Gate::allows('role_enseignant', Auth::user())) {

    //         $heures = 0;
    //         $salaire_annuelle = 0;
    //         $salaire_heures_supplémentaire = 0;
    //         $salaire_vacation = 0;
    //         $total = 0;
    //         $id_user = Auth::id();
    //         $Enseignant = Enseignant::where('id_user', $id_user)->first();
    //         $id_grade = $Enseignant->id_grade;
    //         $grade = Grade::where('id', $id_grade)->first();
    //         $charge_statutaire = $grade->charge_statutaire;

    //         $payement = Paiement::where('id_intervenant', $Enseignant->id)
    //             ->where('annee_univ', $Annee)
    //             ->get(['vh', 'taux_h', 'brut', 'ir', 'net', 'annee_univ', 'semestre']);
    //         //taux_H 
    //         foreach ($payement as $pay) {
    //             $heures += $pay->vh;
    //             if ($heures <= 200 + $charge_statutaire) {
    //                 if ($pay->id_etab == $Enseignant->etablissement) {
    //                     if ($pay->vh <= $charge_statutaire) {
    //                         $salaire_annuelle += $pay->net; //brut
    //                         $total += $salaire_annuelle;
    //                     } else {
    //                         $salaire_heures_supplémentaire += $pay->net; //brut
    //                         $total += $salaire_heures_supplémentaire;
    //                     }
    //                 } else {
    //                     $salaire_vacation += $pay->net; //brut
    //                     $total += $salaire_vacation;
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json([

    //         'salaire_heures_supplémentaire' => $salaire_heures_supplémentaire,
    //         'salaire_vacation' => $salaire_vacation,
    //         'salaire_annuelle' => $salaire_annuelle,
    //         'total' => $total

    //     ]);

    // }


    // public function calculer_Mon_salaire($Annee)
    // {
    //     if (Gate::allows('role_enseignant', Auth::user())) {

    //         $heures = 0;
    //         $salaire_annuelle = 0;
    //         $salaire_heures_supplémentaire = 0;
    //         $salaire_vacation = 0;
    //         $total = 0;
    //         $id_user = Auth::id();
    //         $Enseignant = Enseignant::where('id_user', $id_user)->first();
    //         $id_grade = $Enseignant->id_grade;
    //         $grade = Grade::where('id', $id_grade)->first();
    //         $charge_statutaire = $grade->charge_statutaire;
    //         $taux_horaire = $grade->taux_horaire_vacation;
    //         $annuel_horaire = 0;
    //         $supplementaire_horaire = 0;
    //         $brut_annuel = 0;
    //         $brut_supplementaire = 0;
    //         $ir_annuel = 0;
    //         $ir_suplémentaire = 0;
    //         $payement = Paiement::where('id_intervenant', $Enseignant->id)
    //             ->where('annee_univ', $Annee)
    //             ->get(['vh', 'taux_h', 'brut', 'ir', 'net', 'annee_univ', 'semestre', 'id_etab']);
    //         //taux_H 

    //         foreach ($payement as $pay) {
    //             $heures += $pay->vh;
    //             $heures_supplémentaires+=$supplémentaire_horaire;
    //             if ($heures <= 200 + $charge_statutaire) {

    //                 if ($pay->id_etab == $Enseignant->etablissement) {




    //                         $brut_supplementaire = $pay->net;
    //                         $salaire_heures_supplémentaire=
    //                         $ir_suplémentaire = $brut_supplementaire * 0.38;
    //                         $salaire_annuelle = $brut_annuel - $ir_annuel;
    //                         $salaire_heures_supplémentaire = $brut_supplementaire - $ir_suplémentaire;
    //                         $total = $total + $salaire_annuelle + $salaire_heures_supplémentaire;
    //                     }
    //                 } else {
    //                     $salaire_vacation = $salaire_vacation + $pay->net; //brut
    //                     $total = $total + $salaire_vacation;
    //                 }
    //             } 
    //         }


    //         return response()->json([
    //             'pay' => $pay->id_etab == $Enseignant->etablissement,
    //             'salaire_heures_supplémentaire' => $salaire_heures_supplémentaire,
    //             'salaire_vacation' => $salaire_vacation,
    //             'salaire_annuelle' => floatval($salaire_annuelle),
    //             'total' => $total

    //         ]);
    //     } else {
    //         abort('403');
    //     }


    public function Calcule_Salaire_Enseignant($ppr, $Annee)
    {
        if (Gate::allows('role_president', Auth::user())) {
            $heures = 0;
            $salaire_annuelle = 0;
            $salaire_heures_supplémentaire = 0;
            $salaire_vacation = 0;
            $total = 0;

            $Enseignant = Enseignant::where('ppr', $ppr)->first();
            $id_grade = $Enseignant->id_grade;
            $grade = Grade::where('id', $id_grade)->first();
            $taux_horaire = $grade->taux_horaire_vacation;
            $charge_statutaire = $grade->charge_statutaire;

            $payement = Paiement::where('id_intervenant', $Enseignant->id)
                ->where('annee_univ', $Annee)
                ->get(['vh', 'taux_h', 'brut', 'ir', 'net', 'annee_univ', 'semestre', 'id_etab']);
            //taux_H 

            foreach ($payement as $pay) {
                $heures += $pay->vh;
                if ($heures <= 200 + $charge_statutaire) {
                    if ($pay->id_etab == $Enseignant->etablissement) {
                        if ($pay->vh <= $charge_statutaire) {

                            $salaire_annuelle = $pay->net; //brut
                            $total = $total + $salaire_annuelle;
                        } else {
                            $annuel_horaire = $charge_statutaire;
                            $supplementaire_horaire = $pay->vh - $charge_statutaire;
                            $brut_annuel = $charge_statutaire * $taux_horaire;
                            $brut_supplementaire = $supplementaire_horaire * $taux_horaire;
                            $ir_annuel = $brut_annuel * 0.38;
                            $ir_suplémentaire = $brut_supplementaire * 0.38;
                            $salaire_annuelle = $brut_annuel - $ir_annuel;
                            $salaire_heures_supplémentaire = $brut_supplementaire - $ir_suplémentaire;
                            $total = $total + $salaire_annuelle + $salaire_heures_supplémentaire;
                        }
                    } else {
                        $salaire_vacation = $salaire_vacation + $pay->net; //brut
                        $total = $total + $salaire_vacation;
                    }
                }
            }


            return response()->json([
                'pay' => $pay->id_etab == $Enseignant->etablissement,
                'salaire_heures_supplémentaire' => $salaire_heures_supplémentaire,
                'salaire_vacation' => $salaire_vacation,
                'salaire_annuelle' => floatval($salaire_annuelle),
                'total' => $total

            ]);
        } else {
            abort('403');
        }
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
        try {
            $paiement = Paiement::findOrfail($id);
            $paiement->delete();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Le paiement désiré est supprimée avec succès'
            ]);

        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}