<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FormatHelperTest extends TestCase
{
    /**
     * Test rupiah helper function formatting.
     */
    public function test_rupiah_formats_currency_correctly(): void
    {
        $this->assertEquals('Rp 1.250.000', rupiah(1250000));
        $this->assertEquals('Rp 0', rupiah(0));
        $this->assertEquals('Rp 3.500', rupiah(3500));
        $this->assertEquals('Rp 100.000.000', rupiah(100000000));
    }

    /**
     * Test clean_phone_number normalizes local formats to 62...
     */
    public function test_clean_phone_number_formats_correctly(): void
    {
        $this->assertEquals('6281234567890', clean_phone_number('081234567890'));
        $this->assertEquals('6281234567890', clean_phone_number('81234567890'));
        $this->assertEquals('6281234567890', clean_phone_number('+62 812-3456-7890'));
        $this->assertEquals('6281234567890', clean_phone_number('6281234567890'));
    }

    /**
     * Test wa_link generates valid WhatsApp click-to-chat URL.
     */
    public function test_wa_link_generates_correct_url(): void
    {
        $link = wa_link('Halo Admin, mau tanya undangan.', '081234567890');

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $link);
        $this->assertStringContainsString(urlencode('Halo Admin, mau tanya undangan.'), $link);
    }
}
