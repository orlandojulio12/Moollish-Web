<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    public function log(array $data): void
    {
        $url = env('GOOGLE_SHEETS_WEBHOOK_URL');

        if (empty($url)) {
            Log::warning('GoogleSheetsService: GOOGLE_SHEETS_WEBHOOK_URL not configured.');
            return;
        }

        try {
            Http::timeout(5)->post($url, [
                'timestamp'             => now()->format('Y-m-d H:i:s'),
                'name'                  => $data['name'],
                'email'                 => $data['email'],
                'phone'                 => $data['phone'] ?? '',
                'user_type'             => $data['user_type'],
                'sat2farm_expectations' => implode(', ', $data['sat2farm_expectations']),
                'improvement_goals'     => implode(', ', $data['improvement_goals']),
                'message'               => $data['message'] ?? '',
                'source'                => 'ContactForm - moollish.com',
            ]);
        } catch (\Exception $e) {
            Log::error('GoogleSheetsService POST failed: ' . $e->getMessage());
        }
    }
}
