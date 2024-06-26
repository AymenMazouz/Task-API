<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreTaskRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Change this to implement authorization logic if needed
    }

    /**
     * Obtient les règles de validation qui s'appliquent à la requête.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'nullable|string|in:en attente,en cours,terminee',
            'date_echeance' => 'required|date|after_or_equal:today',
        ];
    }

    /**
     * Obtient les messages de validation personnalisés.
     * @return array
     */
    public function messages()
    {
        return [
            'titre.required' => 'Le champ titre est obligatoire.',
            'date_echeance.required' => 'Le champ date échéance est obligatoire.',
            'date_echeance.after_or_equal' => 'La date d\'échéance ne peut pas être dans le passé.',
        ];
    }


     /**
     * Gère un échec de validation.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 400,
            'msg' => 'Validation Error',
            'errors' => $validator->errors()
        ], 400);

        throw new HttpResponseException($response);
    }
}
