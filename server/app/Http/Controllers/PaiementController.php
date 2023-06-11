<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Exception;

class PaiementController extends Controller
{
    
    public function Afichage_Mon_Payement($Annee)
    {   
        if (Gate::allows('role_enseignant', Auth::user())){
        $id_user=Auth::id();
        $Enseignant=Enseignant::where('id_user',$id_user)->first();
        
        $payement=Payement::where('id_intervenant',$Enseignant->id)
        ->where('Anee_univ',$Annee)
        ->get(['VH','Taux_H','Brut','IR','Net','Anee_univ','semestre']);
        return  $payement;
    }
else{
    abort('403');
}}
    public function Calcule_Mon_Salaire($Annee){
        if (Gate::allows('role_enseignant', Auth::user())){
       $heures=0;
       $salaire_annuelle=0;
       $salaire_heures_supplémentaire=0;
       $salaire_vacation=0;
       $total=0;
       $id_user=Auth::id();
       $Enseignant=Enseignant::where('id_user',$id_user)->first();
       $id_grade=$Enseignant->id_grade;
    $grade=Grade::where('id',$id_grade)->first();
    $charge_statutaire=$grade->charge_statutaire;

        $payement=Payement::where('id_intervenant',$enseignant)
        ->where('Anee_univ',$Anne)
        ->get(['VH','Taux_H','Brut','IR','Net','Anee_univ','semestre']);
        foreach($payement as $pay){
            $heures+=$pay->VH;
            if($heures<=200+$charge_statutaire){
                if($pay->id_etab == $Enseignant->Etablissement){
                    if($pay->VH<$charge_statutaire){
                        $salaire_annuelle+=$pay->net;
                        $total+=$salaire_annuelle;
                    }else{
                        $salaire_vacation+=$pay->net;
                        $total+=$salaire_vacation;
                    }
                }else{
                    $salaire_heures_supplémentaire+=$pay->net;
                    $total+=$salaire_heures_supplémentaire;
                }
            }

        }}
    }
        public function Calcule_Salaire_Enseignant($ppr,$Annee){
            if (Gate::allows('role_president', Auth::user())){
            $heures=0;
            $salaire_annuelle=0;
            $salaire_heures_supplémentaire=0;
            $salaire_vacation=0;
            $total=0;
            
            $Enseignant=Enseignant::where('ppr',$ppr)->first();
            $id_grade=$Enseignant->id_grade;
         $grade=Grade::where('id',$id_grade)->first();
         $charge_statutaire=$grade->charge_statutaire;
     
             $payement=Payement::where('id_intervenant',$enseignant)
             ->where('Anee_univ',$Anne)
             ->get(['VH','Taux_H','Brut','IR','Net','Anee_univ','semestre']);
             foreach($payement as $pay){
                $heures+=$pay->VH;
                 if($heures<=200+$charge_statutaire){
                     if($pay->id_etab == $Enseignant->Etablissement){
                         if($pay->VH<$charge_statutaire){
                             $salaire_annuelle+=$pay->net;
                             $total+=$salaire_annuelle;
                         }else{
                             $salaire_vacation+=$pay->net;
                             $total+=$salaire_vacation;
                         }
                     }else{
                         $salaire_heures_supplémentaire+=$pay->net;
                         $total+=$salaire_heures_supplémentaire;
                     }
                 }
                 
             }

    }else{
        abort('403');
    }
}
    
   
}

