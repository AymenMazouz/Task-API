<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class RegisterUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtient les règles de validation qui s'appliquent à la requête.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'name' => 'required|string',
            'email' => 'required|email|unique:users|max:155',
            'password' => 'required|min:4',
        ];
    }

    /**
     * Obtient les messages de validation personnalisés.
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Le champ name est obligatoire.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.unique' => 'Cette adresse mail est deja utilisee',

            'password.required' => 'Le champ password est obligatoire.',
            'role.string' => 'Le champ rôle doit être une chaîne de caractères.',
            'role.in' => 'Le champ rôle doit être soit "admin" soit "user".',
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
            'msg' => 'erreur de validation',
            'errors' => $validator->errors()
        ], 400);

        throw new HttpResponseException($response);
    }
}
