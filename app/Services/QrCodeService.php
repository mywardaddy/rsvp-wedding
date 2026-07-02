<?php

namespace App\Services;

use App\Models\Guest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    /**
     * Generate QR code for a guest and store as image.
     */
    public function generateForGuest(Guest $guest): string
    {
        if (empty($guest->qr_code)) {
            $guest->update(['qr_code' => 'WG-' . strtoupper(\Str::random(10))]);
        }

        $directory = 'qrcodes/' . $guest->event_id;

        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $filename = $directory . '/' . $guest->qr_code . '.svg';

        $qrContent = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->color(60, 60, 60)
            ->backgroundColor(255, 255, 255)
            ->generate($guest->qr_code);

        Storage::disk('public')->put($filename, $qrContent);

        return $filename;
    }

    /**
     * Generate QR codes for multiple guests.
     */
    public function generateBulk(array $guestIds): array
    {
        $results = [];
        $guests = Guest::whereIn('id', $guestIds)->get();

        foreach ($guests as $guest) {
            $results[$guest->id] = $this->generateForGuest($guest);
        }

        return $results;
    }

    /**
     * Get QR code image path for a guest.
     */
    public function getQrCodePath(Guest $guest): ?string
    {
        $filename = 'qrcodes/' . $guest->event_id . '/' . $guest->qr_code . '.svg';

        if (Storage::disk('public')->exists($filename)) {
            return Storage::disk('public')->url($filename);
        }

        // Generate if not exists
        $this->generateForGuest($guest);
        return Storage::disk('public')->url($filename);
    }

    /**
     * Generate inline QR code SVG for display.
     */
    public function generateInlineSvg(string $data, int $size = 200): string
    {
        return QrCode::format('svg')
            ->size($size)
            ->margin(1)
            ->color(60, 60, 60)
            ->backgroundColor(255, 255, 255)
            ->generate($data);
    }
}
