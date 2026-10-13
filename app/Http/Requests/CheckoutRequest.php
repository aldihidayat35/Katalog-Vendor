<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^(08|628|\+628)[0-9]{8,13}$/'],
            'instagram_account' => ['nullable', 'string', 'max:100'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'shipping_method' => ['required', 'string', 'max:100'],
            'payment_option' => ['required', 'in:dp,full'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom Indonesian validation error messages.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_email.required' => 'Alamat email wajib diisi.',
            'customer_email.email' => 'Format alamat email tidak valid.',
            'customer_phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'customer_phone.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format Indonesia (contoh: 08123456789 atau 628123456789).',
            'event_date.required' => 'Tanggal acara wajib diisi.',
            'event_date.date' => 'Format tanggal acara tidak valid.',
            'event_date.after_or_equal' => 'Tanggal acara harus hari ini atau tanggal di masa mendatang.',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi.',
            'shipping_method.required' => 'Metode pengiriman wajib dipilih.',
            'payment_option.required' => 'Opsi pembayaran wajib dipilih.',
            'payment_option.in' => 'Pilihan pembayaran harus berupa Uang Muka (DP) atau Full Payment.',
        ];
    }
}
