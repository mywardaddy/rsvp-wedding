<?php

namespace Database\Seeders;

use App\Models\PricingPackage;
use App\Models\PricingFeature;
use Illuminate\Database\Seeder;

class PricingPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Silver Package
        $silver = PricingPackage::updateOrCreate(
            ['slug' => 'silver'],
            [
                'name' => 'Silver',
                'description' => 'Paket dasar untuk undangan digital yang elegan dan fungsional.',
                'price' => 999000,
                'discount_type' => 'none',
                'discount_value' => 0,
                'badge' => null,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $silverFeatures = [
            ['name' => 'Undangan Digital', 'is_included' => true],
            ['name' => 'RSVP', 'is_included' => true],
            ['name' => 'QR Code Check-in', 'is_included' => true],
            ['name' => 'Buku Tamu Digital', 'is_included' => true],
            ['name' => 'Galeri Foto (10 Foto)', 'is_included' => true],
            ['name' => 'Background Music', 'is_included' => true],
            ['name' => 'Countdown', 'is_included' => true],
            ['name' => 'Google Maps', 'is_included' => true],
            ['name' => 'Live Streaming', 'is_included' => false],
            ['name' => 'Unlimited Guest', 'is_included' => false],
            ['name' => 'Premium Theme', 'is_included' => false],
            ['name' => 'Custom Domain', 'is_included' => false],
            ['name' => 'Analytics', 'is_included' => false],
            ['name' => 'WhatsApp Invitation', 'is_included' => false],
        ];

        $silver->features()->delete();
        foreach ($silverFeatures as $index => $feature) {
            $silver->features()->create(array_merge($feature, ['sort_order' => $index]));
        }

        // Gold Package
        $gold = PricingPackage::updateOrCreate(
            ['slug' => 'gold'],
            [
                'name' => 'Gold',
                'description' => 'Paket premium dengan fitur lengkap untuk pengalaman undangan yang sempurna.',
                'price' => 1499000,
                'discount_type' => 'none',
                'discount_value' => 0,
                'badge' => 'Most Popular',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $goldFeatures = [
            ['name' => 'Undangan Digital', 'is_included' => true],
            ['name' => 'RSVP', 'is_included' => true],
            ['name' => 'QR Code Check-in', 'is_included' => true],
            ['name' => 'Buku Tamu Digital', 'is_included' => true],
            ['name' => 'Galeri Foto (30 Foto)', 'is_included' => true],
            ['name' => 'Background Music', 'is_included' => true],
            ['name' => 'Countdown', 'is_included' => true],
            ['name' => 'Google Maps', 'is_included' => true],
            ['name' => 'Live Streaming', 'is_included' => true],
            ['name' => 'Unlimited Guest', 'is_included' => true],
            ['name' => 'Premium Theme', 'is_included' => true],
            ['name' => 'WhatsApp Invitation', 'is_included' => true],
            ['name' => 'Custom Domain', 'is_included' => false],
            ['name' => 'Custom Background', 'is_included' => false],
            ['name' => 'Analytics', 'is_included' => false],
            ['name' => 'Scanner Petugas', 'is_included' => false],
        ];

        $gold->features()->delete();
        foreach ($goldFeatures as $index => $feature) {
            $gold->features()->create(array_merge($feature, ['sort_order' => $index]));
        }

        // Platinum Package
        $platinum = PricingPackage::updateOrCreate(
            ['slug' => 'platinum'],
            [
                'name' => 'Platinum',
                'description' => 'Paket eksklusif dengan semua fitur premium tanpa batas untuk hari istimewa Anda.',
                'price' => 2999000,
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'badge' => 'Best Value',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        $platinumFeatures = [
            ['name' => 'Undangan Digital', 'is_included' => true],
            ['name' => 'RSVP', 'is_included' => true],
            ['name' => 'QR Code Check-in', 'is_included' => true],
            ['name' => 'Buku Tamu Digital', 'is_included' => true],
            ['name' => 'Galeri Foto Unlimited', 'is_included' => true],
            ['name' => 'Background Music', 'is_included' => true],
            ['name' => 'Countdown', 'is_included' => true],
            ['name' => 'Google Maps', 'is_included' => true],
            ['name' => 'Live Streaming', 'is_included' => true],
            ['name' => 'Unlimited Guest', 'is_included' => true],
            ['name' => 'Premium Theme', 'is_included' => true],
            ['name' => 'Custom Domain', 'is_included' => true],
            ['name' => 'Custom Background', 'is_included' => true],
            ['name' => 'Analytics', 'is_included' => true],
            ['name' => 'Scanner Petugas', 'is_included' => true],
            ['name' => 'WhatsApp Invitation', 'is_included' => true],
        ];

        $platinum->features()->delete();
        foreach ($platinumFeatures as $index => $feature) {
            $platinum->features()->create(array_merge($feature, ['sort_order' => $index]));
        }
    }
}
