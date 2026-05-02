<?php

namespace App\Jobs\WhatsApp;

use App\Enums\WhatsApp\MessageTypeEnum;
use App\Http\Repositories\Conversation\MessageRepository;
use App\Http\Services\Integrations\IntegrationGoogleDriveService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class WhatsAppUploadMediaJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int    $messageId,
        private array  $media,
        private string $contentType,
        private string $whatsapp,
        private string $messageType = MessageTypeEnum::SENT,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        MessageRepository $messageRepository,
        IntegrationGoogleDriveService $googleDrive
    ): void {
        $decoded   = base64_decode($this->media['data']);
        $ext       = guessExtension($this->media['mimetype']);
        $fileName  = $this->contentType . '-' . now()->format('Ymd_His') . '.' . $ext;
        $cloudPath = $googleDrive->buildPath($this->whatsapp, $this->messageType, $this->contentType, $fileName);

        $attachmentUrl = $googleDrive->upload($cloudPath, $decoded);
        $messageRepository->update($this->messageId, ['attachment' => $attachmentUrl]);
    }
}
