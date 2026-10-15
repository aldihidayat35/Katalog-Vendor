<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PromotionPopupRequest extends FormRequest
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
            'popup_enabled' => ['nullable', 'boolean'],
            'popup_title' => ['required', 'string', 'max:255'],
            'popup_image' => ['nullable', 'file', 'mimes:png,jpg,jpeg,webp,svg', 'max:10240'],
            'remove_image' => ['nullable', 'boolean'],
            'popup_link_type' => ['required', 'in:catalog,whatsapp,custom'],
            'popup_custom_url' => ['nullable', 'url', 'max:500', 'required_if:popup_link_type,custom'],
            'popup_whatsapp_message' => ['nullable', 'string', 'max:500'],
            'popup_delay_seconds' => ['required', 'numeric', 'min:0', 'max:10'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'popup_title.required' => 'Judul / label promosi wajib diisi.',
            'popup_image.mimes' => 'Gambar poster harus berupa format JPG, PNG, WEBP, atau SVG.',
            'popup_image.max' => 'Ukuran file gambar poster maksimal 10 MB.',
            'popup_link_type.required' => 'Pilihan tipe link tujuan wajib dipilih.',
            'popup_link_type.in' => 'Tipe link tujuan tidak valid.',
            'popup_custom_url.required_if' => 'URL custom tujuan wajib diisi jika memilih tipe Custom Link.',
            'popup_custom_url.url' => 'Format URL custom tujuan harus berupa link valid (diawali https:// atau http://).',
            'popup_delay_seconds.required' => 'Waktu tunda kemunculan popup wajib ditentukan.',
            'popup_delay_seconds.numeric' => 'Waktu tunda harus berupa angka detik.',
        ];
    }
}
