<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    protected $fillable = ['conversation_id', 'senderable_id', 'senderable_type', 'message_id', 'channel', 'content_type', 'body', 'attachment', 'read_at'];
    protected $hidden = ['created_at', 'updated_at'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function senderable(): MorphTo
    {
        return $this->morphTo();
    }
}
