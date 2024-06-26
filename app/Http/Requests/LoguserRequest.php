<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoguserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',

        ];
    }

  /**
     * Obtient les messages de validation personnalisés.
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Le champ email est obligatoire.',
            'email.email' => 'Le champ email doit être une adresse email valide.',
            'email.exists' => 'Cette adresse email n\'existe pas dans nos enregistrements.',
            'password.required' => 'Le champ password est obligatoire.',
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
