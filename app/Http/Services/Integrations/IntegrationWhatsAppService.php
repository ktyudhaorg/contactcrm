<?php

namespace App\Http\Services\Integrations;

use GuzzleHttp\Client;

class IntegrationWhatsAppService
{
    protected $client;

    public function __construct(protected IntegrationHmacService $integrationHmacService)
    {
        $this->client = new Client([
            'base_uri' => config('services.whatsapp.url'),
            'timeout' => 120,
        ]);
    }

    /**
     * Universal Request Handler (Auto HMAC)
     */
    private function sendRequest(string $method, string $endpoint, array $payload = []): array
    {
        $body = $method === 'GET' ? '' : json_encode($payload, JSON_UNESCAPED_UNICODE);

        $headers = array_merge(
            $this->integrationHmacService->generateHeaders(),
            ['Content-Type' => 'application/json']
        );

        $options = ['headers' => $headers];

        if ($method === 'POST') {
            $options['body'] = $body;
        }

        if ($method === 'GET' && !empty($payload)) {
            $options['query'] = $payload;
        }

        $response = $this->client->request($method, $endpoint, $options);

        return json_decode($response->getBody()->getContents(), true);
    }
    /**
     * Universal Request Multipart Handler (Auto HMAC)
     */
    private function sendMultipart(
        string $endpoint,
        array $multipart
    ): array {
        $headers = $this->integrationHmacService->generateHeaders();

        $response = $this->client->post($endpoint, [
            'headers'   => $headers,
            'multipart' => $multipart,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /** global */
    public function sendGlobalMedia(string $to, string $binary, string $filename,  string $caption): array
    {
        $multipart = [
            [
                'name'     => 'file',
                // 'contents' => fopen($filePath, 'r'),
                // 'filename' => basename($filePath),

                'contents' => $binary,
                'filename' => $filename,
            ],
            [
                'name'     => 'to',
                'contents' => $to,
            ],
            [
                'name'     => 'caption',
                'contents' => $caption,
            ],
        ];

        return $this->sendMultipart('/api/whatsapp/send-media-global', $multipart);
    }

    public function sendGlobalMessage(string $to, string $message): array
    {
        return $this->sendRequest('POST', '/api/whatsapp/send-message-global', [
            'to'      => $to,
            'message' => $message,
        ]);
    }

    /** chats */
    public function getChat(): array
    {
        return $this->sendRequest('GET', '/api/whatsapp/chats');
    }

    public function sendMessage(string $to, string $message): array
    {
        return $this->sendRequest('POST', '/api/whatsapp/send-message', [
            'to'      => $to,
            'message' => $message,
        ]);
    }

    public function sendMediaWithUrl(string $to, string $mediaUrl, string $message): array
    {
        return $this->sendRequest('POST', '/api/whatsapp/send-media', [
            'to'        => $to,
            'mediaUrl'  => $mediaUrl,
            'caption'   => $message,
        ]);
    }

    public function getChatMessage(string $to, int $limit = 10): array
    {
        return $this->sendRequest('GET', '/api/whatsapp/chat-messages', [
            'to'    => $to,
            'limit' => $limit,
        ]);
    }

    /** groups */
    public function getGroup(): array
    {
        return $this->sendRequest('GET', '/api/whatsapp/groups');
    }

    public function sendGroupMessage(string $to, string $message): array
    {
        return $this->sendRequest('POST', '/api/whatsapp/send-message-group', [
            'groupId' => $to,
            'message' => $message,
        ]);
    }

    public function sendGroupMedia(string $to, string $mediaUrl, string $message): array
    {
        return $this->sendRequest('POST', '/api/whatsapp/send-media-group', [
            'groupId'   => $to,
            'mediaUrl'  => $mediaUrl,
            'caption'   => $message,
        ]);
    }

    public function getGroupMessage(string $to, int $limit = 10): array
    {
        return $this->sendRequest('GET', '/api/whatsapp/group-messages', [
            'groupId' => $to,
            'limit'   => $limit,
        ]);
    }
}
