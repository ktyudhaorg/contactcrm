<?php

namespace App\Http\Services\UseCases\Message;

use App\Enums\WhatsApp\MessageTypeEnum;
use App\Http\Repositories\Contact\ContactRepository;
use App\Http\Repositories\Conversation\ConversationRepository;
use App\Http\Repositories\Conversation\MessageRepository;
use App\Http\Services\Integrations\IntegrationGoogleDriveService;
use App\Jobs\WhatsApp\WhatsAppUploadMediaJob;

class UseCaseMessageService
{
    public function __construct(
        protected ContactRepository $contactRepository,
        protected MessageRepository $messageRepository,
        protected ConversationRepository $conversationRepository,
        protected IntegrationGoogleDriveService $integrationGoogleDriveService,
    ) {}

    public function store($data)
    {
        $whatsapp = normalizePhoneNumber($data['to']);
        $contact = $this->contactRepository->firstOrCreate(
            ['whatsapp' => $whatsapp],
            ['name'  => $whatsapp]
        );
        $conversation = $this->conversationRepository->firstOrCreate(
            [
                'contact_id' => $contact->id,
                'channel' => $data['channel']
            ],
            [
                'assigned_to' => $contact->assigned_to ?? null,
                'status' => 'open'
            ]
        );

        $message = $this->messageRepository->create([
            'conversation_id' => $conversation->id,
            'message_id'      => $data['id'] ?? null,
            'senderable_id'   => $data['senderable_id'],
            'senderable_type' => $data['senderable_type'],
            'channel'         => $data['channel'],
            'body'            => $data['message'] ?? $data['media']['caption'] ?? null,
        ]);

        if (!empty($data['media']['data'])) {
            WhatsAppUploadMediaJob::dispatch(
                $message->id,
                $data['media'],
                $data['content_type'],
                $whatsapp,
                MessageTypeEnum::SENT
            );
        }

        return $message->refresh();
    }

    public function storeIncoming($data)
    {
        $whatsapp = normalizePhoneNumber($data['from']);
        $contact = $this->contactRepository->firstOrCreate(
            ['whatsapp' => $whatsapp],
            ['name'  => $data['name']]
        );

        $conversation = $this->conversationRepository->firstOrCreate(
            [
                'contact_id' => $contact->id,
                'channel' => $data['channel']
            ],
            [
                'assigned_to' => $contact->assigned_to ?? null,
                'status' => 'open'
            ]
        );

        $message = $this->messageRepository->create([
            'conversation_id' => $conversation->id,
            'message_id'      => $data['id'] ?? null,
            'senderable_id'   => $contact->id,
            'senderable_type' => $contact->getMorphClass(),
            'channel'         => $data['channel'],
            'content_type'    => $data['content_type'],
            'body'            => $data['message'],
            'attachment'      => null,
        ]);

        if (!empty($data['media']['data'])) {
            WhatsAppUploadMediaJob::dispatch(
                $message->id,
                $data['media'],
                $data['content_type'],
                $whatsapp,
                MessageTypeEnum::RECEIVED
            );
        }

        return $message->refresh();
    }
}
