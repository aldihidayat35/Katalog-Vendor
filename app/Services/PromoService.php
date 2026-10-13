<?php

namespace App\Services;

use App\Models\Promo;
use Carbon\Carbon;

class PromoService
{
    /**
     * Validate promo code against given subtotal.
     *
     * @return array{valid: bool, promo: ?Promo, discount: int, message: string}
     */
    public function validatePromo(string $code, int $subtotal): array
    {
        $code = strtoupper(trim($code));

        if (empty($code)) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Silakan masukkan kode promo.',
            ];
        }

        $promo = Promo::where('code', $code)->first();

        if (! $promo) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo tidak ditemukan.',
            ];
        }

        if (! $promo->is_active) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo ini sedang tidak aktif.',
            ];
        }

        $now = Carbon::now();

        if ($now->lt($promo->start_date)) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo belum mulai berlaku (mulai '.$promo->start_date->translatedFormat('d M Y').').',
            ];
        }

        if ($now->gt($promo->end_date)) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kode promo sudah kedaluwarsa.',
            ];
        }

        if ($promo->usage_limit !== null && $promo->used_count >= $promo->usage_limit) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Kuota penggunaan kode promo ini telah habis.',
            ];
        }

        if ($promo->min_purchase !== null && $subtotal < $promo->min_purchase) {
            return [
                'valid' => false,
                'promo' => null,
                'discount' => 0,
                'message' => 'Minimal belanja untuk kode promo ini adalah '.rupiah($promo->min_purchase).'.',
            ];
        }

        $discount = $this->calculateDiscount($promo, $subtotal);

        return [
            'valid' => true,
            'promo' => $promo,
            'discount' => $discount,
            'message' => 'Kode promo '.$promo->code.' berhasil diterapkan! Diskon: '.rupiah($discount).'.',
        ];
    }

    /**
     * Calculate discount amount.
     */
    public function calculateDiscount(Promo $promo, int $subtotal): int
    {
        if ($promo->discount_type === 'percent') {
            $discount = (int) round(($subtotal * $promo->discount_amount) / 100);
            if ($promo->max_discount !== null && $discount > $promo->max_discount) {
                return (int) $promo->max_discount;
            }

            return $discount;
        }

        return min((int) $promo->discount_amount, $subtotal);
    }
}
