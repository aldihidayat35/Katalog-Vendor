<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'whatsapp_number' => ['required', 'string', 'min:9', 'max:25'],
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'shipping_methods' => ['nullable', 'array'],
            'consultation_message' => ['required', 'string'],
            'site_logo' => ['nullable', 'file', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'site_favicon' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg,svg,webp', 'max:1024'],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_favicon' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'whatsapp_number.required' => 'Nomor WhatsApp admin wajib diisi.',
            'site_name.required' => 'Nama situs wajib diisi.',
            'site_tagline.required' => 'Tagline situs wajib diisi.',
            'contact_email.required' => 'Email kontak wajib diisi.',
            'contact_email.email' => 'Format email kontak tidak valid.',
            'instagram_url.url' => 'Format tautan Instagram harus berupa URL valid.',
            'consultation_message.required' => 'Template pesan konsultasi wajib diisi.',
            'site_logo.mimes' => 'Logo harus berformat PNG, JPG, JPEG, SVG, atau WEBP.',
            'site_logo.max' => 'Ukuran logo maksimal 2MB.',
            'site_favicon.mimes' => 'Favicon harus berformat ICO, PNG, JPG, JPEG, SVG, atau WEBP.',
            'site_favicon.max' => 'Ukuran favicon maksimal 1MB.',
        ];
    }
}
