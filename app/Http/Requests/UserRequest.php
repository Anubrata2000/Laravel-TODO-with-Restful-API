<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        $isUpdate = $this->isMethod( 'PUT' ) || $this->isMethod( 'PATCH' );
        $userId   = $this->route( 'id' ) ?? $this->user()?->id;

        return [
            'name'     => ( $isUpdate ? 'sometimes|' : '' ) . 'required|string|max:255',
            'email'    => ( $isUpdate ? 'sometimes|' : '' ) . 'required|email|unique:users,email,' . $userId,
            'password' => ( $isUpdate ? 'nullable|' : '' ) . 'required|string|min:8',
        ];
    }
}