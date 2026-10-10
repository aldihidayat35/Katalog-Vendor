<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $imageRule = $this->isMethod('post')
            ? ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048']
            : ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'];

        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => $imageRule,
            'link_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul banner wajib diisi.',
            'image.required' => 'File gambar banner wajib diunggah.',
            'image.mimes' => 'Format gambar banner harus JPG, JPEG, PNG, atau WEBP.',
            'image.max' => 'Ukuran gambar banner maksimal 2 MB.',
            'sort_order.required' => 'Urutan banner wajib diisi.',
            'sort_order.integer' => 'Urutan banner harus berupa angka.',
        ];
    }
}
