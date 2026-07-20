<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMService
{
    public static function sendSilentNotification($fcmToken, array $data = [])
    {
        $path = storage_path('app/firebase-service-account.json');
        if (!file_exists($path)) {
            Log::error('FCM Service Account JSON file not found at: ' . $path);
            throw new \Exception('Firebase Service Account Key tidak ditemukan. Pastikan file "firebase-service-account.json" ada di folder storage/app.');
        }

        $serviceAccount = json_decode(file_get_contents($path), true);
        if (!$serviceAccount || !isset($serviceAccount['private_key']) || !isset($serviceAccount['client_email']) || !isset($serviceAccount['project_id'])) {
            Log::error('Invalid FCM Service Account JSON format.');
            throw new \Exception('Format file Firebase Service Account Key tidak valid.');
        }

        $accessToken = self::getAccessToken($serviceAccount);
        if (!$accessToken) {
            throw new \Exception('Gagal mendapatkan Google OAuth Access Token.');
        }

        $projectId = $serviceAccount['project_id'];
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'data' => $data,
                'android' => [
                    'priority' => 'HIGH',
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'content-available' => 1,
                        ],
                    ],
                    'headers' => [
                        'apns-priority' => '5',
                        'apns-push-type' => 'background',
                    ]
                ],
            ],
        ];

        $response = Http::withToken($accessToken)->post($url, $payload);

        if ($response->successful()) {
            return true;
        }

        Log::error('FCM Send Error: ' . $response->body());
        return false;
    }

    private static function getAccessToken($serviceAccount)
    {
        $now = time();
        $header = base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = base64UrlEncode(json_encode([
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]));

        $signature = '';
        openssl_sign("$header.$payload", $signature, $serviceAccount['private_key'], 'SHA256');
        $signature = base64UrlEncode($signature);

        $assertion = "$header.$payload.$signature";

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion,
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        Log::error('FCM Auth Token Request Failed: ' . $response->body());
        return null;
    }
}

if (!function_exists('App\Services\base64UrlEncode')) {
    function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
