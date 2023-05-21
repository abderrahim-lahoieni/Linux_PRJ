<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditEtablissementRequest extends FormRequest
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
            'code' => 'unique:etablissements|required|string',
            'nom' => 'required|string',
            'num_tel' => 'string',
            'faxe' => 'string',
            'ville' => 'string',
            'nbre_enseignant' => 'integer'
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
            'code.required' => 'Un code doit etre fourni',
            'nom.required' => 'Un nom doit etre fourni',
            'ville.required' => 'Une ville doit etre fourni',
            'nbre_enseignant.required' => 'Un nombre d enseignant doit etre fourni',
        ];
    }
}
