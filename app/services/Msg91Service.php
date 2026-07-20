<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Msg91Service
{
    /**
     * Verify the access token returned by the MSG91 OTP widget's client-side
     * verifyOtp() success callback. This is the step that actually proves to
     * OUR server that MSG91 confirmed the OTP — never trust the client-side
     * "success" callback alone, since that only proves the browser said so.
     *
     * NOTE: verify this endpoint/payload against the exact snippet MSG91 shows
     * you under OTP Widget > Server-Side Integration in your dashboard once you
     * have an account — that snippet is generated with your widgetId baked in
     * and is the source of truth over this implementation.
     *
     * @return bool
     */
    public static function verifyAccessToken(string $accessToken): bool
    {
        try {
            $response = Http::withHeaders([
                'authkey' => config('msg91.auth_key'),
                'Content-Type' => 'application/json',
            ])->post('https://control.msg91.com/api/v5/widget/verifyAccessToken', [
                'authkey' => config('msg91.auth_key'),
                'access-token' => $accessToken,
            ]);

            $body = $response->json();

            Log::info('MSG91 verifyAccessToken response', ['body' => $body]);

            return $response->successful()
                && isset($body['type'])
                && $body['type'] === 'success';
        } catch (\Throwable $e) {
            Log::error('MSG91 verifyAccessToken failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
