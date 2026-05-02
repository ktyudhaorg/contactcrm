<?php

namespace App\Http\Repositories\Conversation;

use App\Http\Repositories\BaseRepository;
use App\Models\Message;

class MessageRepository extends BaseRepository
{
    public function __construct(protected Message $message)
    {
        parent::__construct($message);
    }
}
