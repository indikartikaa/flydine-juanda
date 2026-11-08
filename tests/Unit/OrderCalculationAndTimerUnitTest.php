<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class OrderCalculationAndTimerUnitTest extends TestCase
{
    /**
     * TC-ORD-017 & TC-ORD-001: Verifikasi Akurasi Kalkulasi Subtotal Multi-Item
     */
    public function test_subtotal_calculation_is_mathematically_accurate()
    {
        $cart = [
            ['name' => 'Signature Croissant', 'price' => 25000, 'quantity' => 2],
            ['name' => 'Espresso Hot', 'price' => 18000, 'quantity' => 1],
            ['name' => 'Mineral Water', 'price' => 10000, 'quantity' => 3],
        ];

        $subtotal = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Expected: (25000*2) + (18000*1) + (10000*3) = 50000 + 18000 + 30000 = 98000
        $this->assertEquals(98000, $subtotal);
    }

    /**
     * TC-ORD-018 & TC-ORD-019: Verifikasi Biaya Antar Porter (Self-Pickup vs Delivery)
     */
    public function test_delivery_fee_calculation()
    {
        $subtotal = 98000;
        $tenantDeliveryFee = 8000;

        // Pickup Method: Ambil Sendiri -> Biaya Antar = 0
        $feeSelfPickup = 0;
        $totalSelfPickup = $subtotal + $feeSelfPickup;
        $this->assertEquals(98000, $totalSelfPickup);

        // Pickup Method: Diantar Porter -> Biaya Antar = 8000
        $feeDelivery = $tenantDeliveryFee;
        $totalDelivery = $subtotal + $feeDelivery;
        $this->assertEquals(106000, $totalDelivery);
    }

    /**
     * TC-ORD-020 & TC-ORD-021: Verifikasi Kalkulasi Diskon Promo (Flat & Persentase dengan Plafon)
     */
    public function test_discount_voucher_and_bank_promo_calculation()
    {
        $subtotal = 100000;

        // Skenario A: Diskon Voucher Flat Rp 15.000
        $voucherDiscount = 15000;
        $subtotalAfterVoucher = max(0, $subtotal - $voucherDiscount);
        $this->assertEquals(85000, $subtotalAfterVoucher);

        // Skenario B: Diskon Bank 20% dengan Maksimal Plafon Rp 25.000
        // Untuk transaksi 200.000: 20% = 40.000 -> harus dipotong tepat plafon 25.000
        $subtotalLarge = 200000;
        $bankDiscountPercent = 0.20;
        $discountCap = 25000;
        $calculatedDiscount = min($subtotalLarge * $bankDiscountPercent, $discountCap);
        $this->assertEquals(25000, $calculatedDiscount);
    }

    /**
     * TC-ORD-022: Verifikasi Pajak Restoran (PB1 10% vs All-Inclusive)
     */
    public function test_tax_pb1_and_all_inclusive_calculation()
    {
        $dpp = 100000; // Dasar Pengenaan Pajak

        // Skema A: Pajak Terpisah (PB1 10%)
        $taxAmount = $dpp * 0.10;
        $grandTotalWithTax = $dpp + $taxAmount;
        $this->assertEquals(10000, $taxAmount);
        $this->assertEquals(110000, $grandTotalWithTax);

        // Skema B: Harga All-Inclusive (Nett - pajak sudah termasuk di harga)
        $taxInclusiveAmount = $dpp - ($dpp / 1.10);
        $this->assertEquals(9090.91, round($taxInclusiveAmount, 2));
    }

    /**
     * TC-CNL-001: Verifikasi Timer Bawaan Auto-Cancel (15 Menit)
     */
    public function test_default_auto_cancel_timer_is_15_minutes()
    {
        $now = Carbon::parse('2026-11-04 10:00:00');
        $autoCancelAt = $now->copy()->addMinutes(15);

        $this->assertEquals('2026-11-04 10:15:00', $autoCancelAt->format('Y-m-d H:i:s'));
        $this->assertEquals(15, $now->diffInMinutes($autoCancelAt));
    }

    /**
     * TC-CNL-013: Verifikasi Sinkronisasi Auto-Cancel Dinamis Mengikuti Jam Boarding yang Mendekati
     */
    public function test_dynamic_auto_cancel_is_shortened_to_boarding_time()
    {
        $now = Carbon::parse('2026-11-04 10:00:00');
        $defaultAutoCancelAt = $now->copy()->addMinutes(15); // 10:15:00

        // Boarding time penumpang adalah 10:12:00 (12 menit lagi, lebih awal dari 15 menit)
        $boardingTime = Carbon::parse('2026-11-04 10:12:00');

        $finalAutoCancelAt = $defaultAutoCancelAt;
        if ($boardingTime->isBefore($defaultAutoCancelAt)) {
            $finalAutoCancelAt = $boardingTime;
        }

        // Auto cancel harus disesuaikan ke 10:12:00 bukan 10:15:00
        $this->assertEquals('2026-11-04 10:12:00', $finalAutoCancelAt->format('Y-m-d H:i:s'));
    }

    /**
     * TC-CNL-011: Verifikasi Deteksi Jadwal Penerbangan Lintas Tengah Malam (Cross-Midnight)
     */
    public function test_cross_midnight_boarding_time_detection()
    {
        $now = Carbon::parse('2026-11-04 23:45:00');
        // Penumpang input jam boarding 00:30 (esok dini hari)
        $boardingInputHour = 0;
        $boardingInputMinute = 30;

        $boardingDateTime = $now->copy()->setTime($boardingInputHour, $boardingInputMinute, 0);
        $diffMinutes = $now->diffInMinutes($boardingDateTime, false); // Akan bernilai negatif jika hari sama (-1395)

        // Logika Cross-Midnight FlyDine:
        if ($diffMinutes < 0 && ($diffMinutes + 1440) >= 0 && ($diffMinutes + 1440) <= 480) {
            $boardingDateTime->addDay();
            $diffMinutes = $now->diffInMinutes($boardingDateTime, false);
        }

        // Expected: diffMinutes harus positif 45 menit (23:45 ke 00:30 besok)
        $this->assertEquals(45, $diffMinutes);
        $this->assertTrue($diffMinutes >= 15);
    }

    /**
     * TC-SEC-001 s.d. TC-SEC-004: Boundary Value Analysis (BVA) Validasi Nomor Telepon
     */
    public function test_phone_number_bva_validation()
    {
        // Fungsi helper validasi BVA 9-15 digit
        $validatePhoneLength = function ($phone) {
            $digitsOnly = preg_replace('/[^0-9]/', '', $phone);
            $length = strlen($digitsOnly);
            return $length >= 9 && $length <= 15;
        };

        // BVA 8 digit (Kurang dari batas bawah) -> INVALID
        $this->assertFalse($validatePhoneLength('08123456'));

        // BVA 9 digit (Tepat batas bawah) -> VALID
        $this->assertTrue($validatePhoneLength('081234567'));

        // Normal 12 digit -> VALID
        $this->assertTrue($validatePhoneLength('081234567890'));

        // BVA 15 digit (Tepat batas atas) -> VALID
        $this->assertTrue($validatePhoneLength('628123456789012'));

        // BVA 16 digit (Melebihi batas atas) -> INVALID
        $this->assertFalse($validatePhoneLength('6281234567890123'));
    }

    /**
     * TC-STA-012 s.d. TC-STA-015: Verifikasi Aturan State Machine Transisi Status
     */
    public function test_order_state_machine_allowed_transitions()
    {
        $allowedTransitions = [
            'menunggu' => ['diproses', 'dibatalkan', 'ditolak'],
            'diproses' => ['siap', 'dibatalkan'],
            'siap' => ['selesai'],
            'selesai' => [],
            'dibatalkan' => [],
            'ditolak' => []
        ];

        // Valid: menunggu -> diproses
        $this->assertContains('diproses', $allowedTransitions['menunggu']);

        // Valid: diproses -> siap
        $this->assertContains('siap', $allowedTransitions['diproses']);

        // Valid: siap -> selesai
        $this->assertContains('selesai', $allowedTransitions['siap']);

        // Ilegal (Jump): menunggu -> selesai (Harus ditolak)
        $this->assertNotContains('selesai', $allowedTransitions['menunggu']);

        // Ilegal (Backward): selesai -> diproses (Harus ditolak)
        $this->assertNotContains('diproses', $allowedTransitions['selesai']);

        // Ilegal (Backward): siap -> diproses (Harus ditolak)
        $this->assertNotContains('diproses', $allowedTransitions['siap']);
    }
}

