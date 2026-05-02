<?php

namespace App\Http\Services\Integrations;


class IntegrationHmacService
{
    protected string $publicKey;
    protected string $privateKey;
    protected int $time;

    private const TIMESTAMP_TOLERANCE = 300;

    public function __construct()
    {
        $this->publicKey = config('services.whatsapp.hmac_public_key');
        $this->privateKey = config('services.whatsapp.hmac_secret_key');
        $this->time = time();
    }

    public function generateHeaders(): array
    {
        return [
            'X-Key'       => $this->publicKey,
            'X-Timestamp' => $this->time,
            'X-Token'     => $this->sign($this->publicKey . $this->time),
        ];
    }

    public function validate(string $receivedHmac, string $timestamp): bool
    {
        if (!is_numeric($timestamp)) {
            return false;
        }

        $now = time();
        if ($timestamp < ($now - self::TIMESTAMP_TOLERANCE) || $timestamp > $now) {
            return false;
        }

        return hash_equals(
            $this->sign($this->publicKey . $timestamp),
            $receivedHmac
        );
    }

    public function sign(string $data): string
    {
        return base64_encode(hash_hmac('sha256', $data, $this->privateKey, true));
    }
}
