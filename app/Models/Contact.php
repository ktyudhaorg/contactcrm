<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name', 'whatsapp', 'telegram', 'email'];
    protected $hidden = ['created_at', 'updated_at'];

    public function setWhatsappAttribute($value)
    {
        $this->attributes['whatsapp'] = normalizePhoneNumber($value);
    }
}
