<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $promoId = $this->route('promo')?->id ?? $this->route('promo');

        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('promos', 'code')->ignore($promoId)],
            'name' => ['required', 'string', 'max:255'],
            'discount_type' => ['required', 'in:percent,nominal'],
            'discount_amount' => ['required', 'integer', 'min:1'],
            'min_purchase' => ['nullable', 'integer', 'min:0'],
            'max_discount' => ['nullable', 'integer', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('discount_type') === 'percent') {
                $amount = (int) $this->input('discount_amount');
                if ($amount < 1 || $amount > 100) {
                    $validator->errors()->add('discount_amount', 'Nilai diskon persentase harus antara 1% sampai 100%.');
                }
            }
        });
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode promo wajib diisi.',
            'code.unique' => 'Kode promo sudah terdaftar.',
            'name.required' => 'Nama promo wajib diisi.',
            'discount_type.required' => 'Tipe diskon wajib dipilih.',
            'discount_amount.required' => 'Besaran diskon wajib diisi.',
            'discount_amount.integer' => 'Besaran diskon harus berupa angka bulat.',
            'discount_amount.min' => 'Besaran diskon minimal 1.',
            'start_date.required' => 'Tanggal mulai promo wajib diisi.',
            'end_date.required' => 'Tanggal berakhir promo wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
        ];
    }
}
