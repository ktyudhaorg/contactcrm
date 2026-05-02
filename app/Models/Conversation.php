<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['contact_id', 'assigned_to', 'channel', 'status'];
    protected $hidden = ['created_at', 'updated_at'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
