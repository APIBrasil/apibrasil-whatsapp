<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        // Let's get the route param by name to get the User object value
        $user = request()->route('user');

        return [
            'block_mail' => 'nullable|sometimes',
            'cellphone' => 'nullable|sometimes',
            'cpf' => 'nullable',
            'last_login' => 'nullable',
            'last_activity' => 'nullable',
            'last_ip' => 'nullable',
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users,email,'.$user->id,
            'username' => 'required|unique:users,username,'.$user->id,
            'server_whatsapp' => 'sometimes|nullable|string',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
