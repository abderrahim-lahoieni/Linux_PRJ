<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreGradeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'designation' => 'required|string',
            'charge_statutaire' => 'required|integer',
            'taux_horaire_vacation' => 'required|integer'
        ];
    }
    
    public function failedValidation(Validator $validator){

        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => true , 
            'message' => 'Erreur de validation',
            'errorsList' => $validator->errors()
        ]));
    }
    public function messages(){
        return [
            'designation.required' => 'Une designation doit etre fourni',
            'charge_statutaire.required' => 'Une charge statutaire doit etre fourni',
            'taux_horaire_vacation.required' => 'Un taux horaire vacation doit etre fourni'
        ];
    }
}
