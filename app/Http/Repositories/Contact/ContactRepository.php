<?php

namespace App\Http\Repositories\Contact;

use App\Http\Repositories\BaseRepository;
use App\Models\Contact;

class ContactRepository extends BaseRepository
{
    public function __construct(protected Contact $contact)
    {
        parent::__construct($contact);
    }

    public function findByType(string $value, string $type = 'whatsapp')
    {
        return $this->contact::where($type, $value)->first();
    }
}
