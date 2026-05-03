<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Contact extends Model
{
    protected $fillable = ['assigned_to', 'name', 'whatsapp', 'telegram', 'email'];
    protected $hidden = ['created_at', 'updated_at'];

    public function setWhatsappAttribute($value)
    {
        $this->attributes['whatsapp'] = normalizePhoneNumber($value);
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'senderable');
    }
}
