<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StudentRegistrationRequest extends FormRequest
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
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@my\.jru\.edu$/',
            ],
            'year' => ['required', 'in:11,12'],
            'section' => ['required', 'string', 'max:10'],
            'studentid' => ['required', 'string', 'unique:users,student_id'],
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
            'acknowledge' => ['required', 'accepted'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.regex' => 'Email must be a valid @my.jru.edu address.',
            'email.unique' => 'This email is already registered.',
            'studentid.unique' => 'This student ID is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'acknowledge.accepted' => 'You must acknowledge the terms to proceed.',
        ];
    }
}
