<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'instagram_account',
        'event_date',
        'shipping_address',
        'shipping_method',
        'payment_option',
        'promo_id',
        'promo_code',
        'subtotal',
        'discount_total',
        'total_price',
        'notes',
        'status',
        'whatsapp_message',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'discount_total' => 'integer',
            'total_price' => 'integer',
            'event_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'lead' => 'bg-amber-100 text-amber-800',
            'contacted' => 'bg-blue-100 text-blue-800',
            'confirmed' => 'bg-emerald-100 text-emerald-800',
            'completed' => 'bg-purple-100 text-purple-800',
            'cancelled' => 'bg-rose-100 text-rose-800',
            default => 'bg-stone-100 text-stone-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'lead' => $this->is_dp ? 'Menunggu Konfirmasi DP' : 'Menunggu Follow-up (Lead)',
            'contacted' => 'Sudah Dihubungi',
            'confirmed' => $this->is_dp ? 'DP Diterima (Dalam Produksi)' : 'Dikonfirmasi (Lunas)',
            'completed' => $this->is_dp ? 'Lunas & Selesai' : 'Pesanan Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getIsDpAttribute(): bool
    {
        return $this->payment_option === 'dp';
    }

    public function getPaymentLabelAttribute(): string
    {
        $dpPercent = setting('dp_percentage', 50);

        return $this->is_dp ? "Uang Muka (DP {$dpPercent}%)" : 'Full Payment (Lunas)';
    }

    public function getOriginalTotalAttribute(): int
    {
        return max(0, (int) $this->subtotal - (int) $this->discount_total);
    }

    public function getDpAmountAttribute(): int
    {
        return $this->is_dp ? (int) $this->total_price : 0;
    }

    public function getRemainingBalanceAttribute(): int
    {
        if (! $this->is_dp) {
            return 0;
        }

        return max(0, $this->original_total - (int) $this->total_price);
    }
}
