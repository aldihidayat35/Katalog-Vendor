<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class WhatsAppMessageBuilder
{
    /**
     * Build formatted WhatsApp message text according to requirements.
     */
    public function build(Order $order): string
    {
        $siteName = setting('site_name', 'Galeria Invitation');
        $eventDate = $order->event_date
            ? Carbon::parse($order->event_date)->locale('id')->translatedFormat('d F Y')
            : '-';

        $instagram = ! empty($order->instagram_account)
            ? '@'.ltrim($order->instagram_account, '@')
            : '-';

        $lines = [];
        $lines[] = "Halo Admin {$siteName}, saya ingin memesan undangan 🙏";
        $lines[] = '';
        $lines[] = "*No. Pesanan:* {$order->order_number}";
        $lines[] = '';
        $lines[] = '*DATA PEMESAN*';
        $lines[] = "Nama: {$order->customer_name}";
        $lines[] = "Email: {$order->customer_email}";
        $lines[] = "No. HP: {$order->customer_phone}";
        $lines[] = "Instagram: {$instagram}";
        $lines[] = "Tanggal Acara: {$eventDate}";
        $lines[] = '';
        $lines[] = '*PESANAN*';

        $i = 1;
        foreach ($order->items as $item) {
            $categoryName = $item->product?->category?->name ?? 'Undangan';
            $unitPriceFormatted = number_format($item->unit_price, 0, ',', '.');
            $subtotalFormatted = number_format($item->subtotal, 0, ',', '.');
            $isWholesale = $item->isWholesale();
            $wholesaleTag = $isWholesale ? ' [Grosir]' : '';
            $wholesalePriceNote = $isWholesale ? ' (Harga Grosir)' : '';

            $lines[] = "{$i}. {$item->product_name} ({$categoryName}){$wholesaleTag}";
            $lines[] = "   {$item->quantity} pcs × Rp {$unitPriceFormatted}{$wholesalePriceNote} = Rp {$subtotalFormatted}";
            $i++;
        }

        $lines[] = '';
        $lines[] = 'Subtotal: Rp '.number_format($order->subtotal, 0, ',', '.');

        if ($order->discount_total > 0 && ! empty($order->promo_code)) {
            $lines[] = "Diskon ({$order->promo_code}): -Rp ".number_format($order->discount_total, 0, ',', '.');
        }

        $lines[] = '*Total Pesanan: Rp '.number_format($order->total_price, 0, ',', '.').'*';
        $lines[] = 'Catatan Pembayaran: Opsi pembayaran DP / termin dapat dibicarakan langsung 🙏';

        $lines[] = '';
        $lines[] = '*PENGIRIMAN*';
        $lines[] = "Metode: {$order->shipping_method}";
        $lines[] = 'Ongkir: Free Ongkir (Seluruh Wilayah Provinsi Riau)';
        $lines[] = "Alamat: {$order->shipping_address}";
        $lines[] = '';
        $lines[] = 'Catatan: '.($order->notes ?: '-');
        $lines[] = '';
        $lines[] = 'Mohon konfirmasinya, terima kasih 🙏';

        return implode("\n", $lines);
    }
}
