<?php

namespace App\Jobs\Ai;

use App\Ai\Agents\Eklinik\EklinikAgent;
use App\Http\Services\Integrations\IntegrationWhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AiPromptWhatsAppJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 120;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $to, protected string $prompt)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(IntegrationWhatsAppService $integrationWhatsAppService): void
    {
        $agent = new EklinikAgent;
        $message = $agent->prompt($this->prompt);

        $integrationWhatsAppService->sendGlobalMessage($this->to, $message->text);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[AI Prompt Job] Failed', [
            'prompt' => $this->prompt,
            'error' => $exception->getMessage(),
        ]);
    }
}
