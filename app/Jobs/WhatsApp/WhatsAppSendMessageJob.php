<?php

namespace App\Jobs\WhatsApp;

use App\Http\Services\Integrations\IntegrationWhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class WhatsAppSendMessageJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 120;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $to,
        protected ?string $message = null,
        protected ?array $media = null,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(IntegrationWhatsAppService $integrationWhatsAppService): void
    {
        if (! empty($this->media)) {
            $caption = $this->media['caption'] ?? $this->message ?? '';

            $integrationWhatsAppService->sendGlobalMedia(
                to: $this->to,
                binary: base64_decode($this->media['data']),
                filename: $this->media['filename'] ?? 'file',
                caption: $caption,
            );

            return;
        }

        $integrationWhatsAppService->sendMessage($this->to, $this->message);
    }

    /**
     * Failed Jobs
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[WhatsApp Jobs] Failed', [
            'to' => $this->to,
            'messsage' => $this->message,
            'error' => $exception->getMessage(),
        ]);
    }
}
