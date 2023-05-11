<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rules\Password;

use Illuminate\Foundation\Http\FormRequest;

class UserPassUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'old_password'=>'required',
            'password'=>[
                'required', 
                'confirmed',
                Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'password_confirmation'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'old_password.required'=>'Oi beta password de bortoman ta',
            'password.required'=>'new password de',
            'password_confirmation.required'=>'abar thikmoto password de',
        ];
    }
}
