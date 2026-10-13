<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'nullable',
                'string',
                'alpha_dash',
                'max:50',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'regex:/^(08|628|\+628)[0-9]{8,13}$/'],
            'instagram' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format Indonesia (contoh: 08123456789 atau 628123456789).',
        ];
    }
}
