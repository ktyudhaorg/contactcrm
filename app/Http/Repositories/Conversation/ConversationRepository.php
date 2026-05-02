<?php

namespace App\Http\Repositories\Conversation;

use App\Http\Repositories\BaseRepository;
use App\Models\Conversation;

class ConversationRepository extends BaseRepository
{
    public function __construct(protected Conversation $conversation)
    {
        parent::__construct($conversation);
    }
}
