<?php

namespace App\Http\Services\WhatsApp;

use App\Http\Requests\WhatsApp\WhatsAppSendMessageRequest;
use App\Http\Services\Integrations\IntegrationWhatsAppService;
use App\Http\Services\UseCases\Message\UseCaseMessageService;
use App\Jobs\WhatsApp\WhatsAppSendMessageJob;
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

    public function sendMessage(WhatsAppSendMessageRequest $request)
    {
        $validated = $request->validated();

        $validated['channel'] = 'whatsapp';
        $validated['senderable_id'] = $this->user->id;
        $validated['senderable_type'] = $this->user->getMorphClass();

        if ($request->hasFile('media.data')) {
            $file = $request->file('media.data');

            $validated['media']['data']     = base64_encode(file_get_contents($file->getRealPath()));
            $validated['media']['mimetype'] = $file->getMimeType();
            $validated['media']['filename'] = $file->getClientOriginalName();
            $validated['content_type']      = resolveContentType($file->getMimeType());
        }

        $message = $this->useCaseMessageService->store($validated);

        WhatsAppSendMessageJob::dispatch(
            to: $validated['to'],
            message: $validated['message'] ?? null,
            media: $validated['media'] ?? null,
        );

        return $message;
    }

    /** WEBHOOK */
    public function webhook(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'string'],
            'from' => ['required', 'string'],
            'name' => ['required', 'string'],
            'content_type' => ['required', 'string'],
            'message' => ['nullable', 'string'],
            'is_from_me'    => ['required',],
            'media' => ['nullable', 'array'],
            'media.data' => ['required_with:media', 'string'], // base64
            'media.mimetype' => ['required_with:media', 'string'],
            'media.filename' => ['nullable', 'string'],
        ]);

        $validated['channel']     = 'whatsapp';

        return $this->useCaseMessageService->storeIncoming($validated);
    }
}
