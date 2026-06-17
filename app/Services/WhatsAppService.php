<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public function sendWebsiteLink(Siswa $siswa, string $message): void
    {
        if (!$siswa->nomor_telepon) {
            return;
        }

        $token = config('services.fonnte.token');
        $target = $this->normalizeNumber($siswa->nomor_telepon);

        if (!$token) {
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);

    }

    private function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D+/', '', $number);

        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        if (!str_starts_with($number, '62')) {
            return '62' . $number;
        }

        return $number;
    }
}

