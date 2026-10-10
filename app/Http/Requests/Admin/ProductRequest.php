<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('product')?->id ?? $this->route('product');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'type' => ['required', 'in:printed,digital'],
            'short_description' => ['nullable', 'string'],
            'description' => ['required', 'string'],
            'specifications' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'discount_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'dp_price' => ['required', 'integer', 'min:0'],
            'min_order' => ['required', 'integer', 'min:1'],
            'badge' => ['nullable', 'string', 'max:50'],
            'stock_status' => ['required', 'in:ready,preorder,out_of_stock'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $price = (int) $this->input('price', 0);
            $discountPrice = $this->filled('discount_price') && (int) $this->input('discount_price') > 0
                ? (int) $this->input('discount_price')
                : null;
            $finalPrice = $discountPrice ?? $price;
            $dpPrice = (int) $this->input('dp_price', 0);

            if ($dpPrice > $finalPrice) {
                $validator->errors()->add('dp_price', 'Harga DP (' . rupiah($dpPrice) . ') tidak boleh melebihi harga final produk (' . rupiah($finalPrice) . ').');
            }
        });
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'name.required' => 'Nama produk wajib diisi.',
            'slug.required' => 'Slug produk wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan oleh produk lain.',
            'type.required' => 'Tipe produk wajib dipilih.',
            'type.in' => 'Tipe produk harus berupa cetak (printed) atau digital.',
            'description.required' => 'Deskripsi isi paket produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.integer' => 'Harga produk harus berupa angka bulat.',
            'price.min' => 'Harga produk minimal Rp 0.',
            'discount_price.lt' => 'Harga diskon harus lebih kecil dari harga normal.',
            'discount_price.integer' => 'Harga diskon harus berupa angka bulat.',
            'dp_price.required' => 'Harga DP produk wajib diisi.',
            'dp_price.integer' => 'Harga DP harus berupa angka bulat.',
            'min_order.required' => 'Minimal pemesanan wajib diisi.',
            'min_order.min' => 'Minimal pemesanan minimal 1.',
            'stock_status.required' => 'Status stok wajib dipilih.',
            'images.*.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'images.*.max' => 'Ukuran gambar maksimal 2 MB per file.',
        ];
    }
}
