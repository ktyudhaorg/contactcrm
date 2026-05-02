<?php

namespace App\Http\Services\WhatsApp;

use App\Http\Services\Integrations\IntegrationWhatsAppService;
use App\Http\Services\UseCases\Message\UseCaseMessageService;
use App\Jobs\WhatsApp\WhatsAppSendMessageJob;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppService
{
    protected $user;

    public function __construct(
        protected IntegrationWhatsAppService $integrationWhatsAppService,
        protected UseCaseMessageService $useCaseMessageService
    ) {
        $this->user = Auth::guard('api')->user();
    }

    public function chats()
    {
        $response = $this->integrationWhatsAppService->getChat();

        return $response['data'];
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'to'        => ['required', 'string'],
            'message'   =>  ['required', 'string']
        ]);

        $validated['channel'] = 'whatsapp';
        $validated['user'] = $this->user->id;
        $validated['sender_type'] = 'agent';

        $message = $this->useCaseMessageService->store($validated);

        WhatsAppSendMessageJob::dispatch($validated['to'], $validated['message']);

        return $message;
    }

    /** WEBHOOK */
    public function webhook(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'string'],
            'from' => ['required', 'string'],
            'name' => ['required', 'string'],
            'content_type' => ['required', 'string', 'in:text,image,video,audio,document,file'],
            'message' => ['nullable', 'string'],
            'media' => ['nullable', 'array'],
            'media.data' => ['required_with:media', 'string'], // base64
            'media.mimetype' => ['required_with:media', 'string'],
            'media.filename' => ['nullable', 'string'],
        ]);

        $validated['channel']     = 'whatsapp';

        return $this->useCaseMessageService->storeIncoming($validated);
    }
}
