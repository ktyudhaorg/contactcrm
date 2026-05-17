<?php

namespace App\Livewire\WhatsApp;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('WhatsApp')]

class Chat extends Component
{
    public array $contacts = [];

    public array $messages = [];

    public int $activeChat = 1;

    public string $newMessage = '';

    public function mount(): void
    {
        $this->contacts = [
            ['id' => 1, 'name' => 'Kaiya George',   'role' => 'Project Manager',    'time' => '15 mins', 'status' => 'online',  'avatar' => 'https://i.pravatar.cc/40?img=3'],
            ['id' => 2, 'name' => 'Lindsey Curtis',  'role' => 'Designer',           'time' => '30 mins', 'status' => 'online',  'avatar' => 'https://i.pravatar.cc/40?img=12'],
            ['id' => 3, 'name' => 'Zain Geidt',      'role' => 'Content Writer',     'time' => '45 mins', 'status' => 'online',  'avatar' => 'https://i.pravatar.cc/40?img=8'],
            ['id' => 4, 'name' => 'Carla George',    'role' => 'Front-end Developer', 'time' => '2 days',  'status' => 'away',    'avatar' => 'https://i.pravatar.cc/40?img=5'],
            ['id' => 5, 'name' => 'Kaiya George',    'role' => 'Project Manager',    'time' => '15 mins', 'status' => 'online',  'avatar' => 'https://i.pravatar.cc/40?img=33'],
            ['id' => 6, 'name' => 'Lindsey Curtis',  'role' => 'Designer',           'time' => '30 mins', 'status' => 'online',  'avatar' => 'https://i.pravatar.cc/40?img=12'],
            ['id' => 7, 'name' => 'Kaiya George',    'role' => 'Project Manager',    'time' => '15 mins', 'status' => 'online',  'avatar' => 'https://i.pravatar.cc/40?img=15'],
        ];

        $this->messages = [
            ['id' => 1, 'contact_id' => 1, 'sender' => 'them', 'text' => 'I want to make an appointment tomorrow from 2:00 to 5:00pm?', 'time' => '2 hours ago', 'avatar' => 'https://i.pravatar.cc/40?img=12'],
            ['id' => 2, 'contact_id' => 1, 'sender' => 'me',   'text' => "If don't like something, I'll stay away from it.", 'time' => '2 hours ago'],
            ['id' => 3, 'contact_id' => 1, 'sender' => 'them', 'text' => 'I want more detailed information.', 'time' => '2 hours ago', 'avatar' => 'https://i.pravatar.cc/40?img=12'],
            ['id' => 4, 'contact_id' => 1, 'sender' => 'me',   'text' => "If don't like something, I'll stay away from it.", 'time' => ''],
            ['id' => 5, 'contact_id' => 1, 'sender' => 'me',   'text' => 'They got there early, and got really good seats.', 'time' => '2 hours ago'],
            ['id' => 6, 'contact_id' => 1, 'sender' => 'them', 'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=300&q=80', 'caption' => 'Please preview the image', 'time' => '2 hours ago', 'avatar' => 'https://i.pravatar.cc/40?img=12'],
            ['id' => 5, 'contact_id' => 2, 'sender' => 'me',   'text' => 'They got there early, and got really good seats.', 'time' => '2 hours ago'],
        ];
    }

    public function setActiveChat(int $id): void
    {
        $this->activeChat = $id;
    }

    public function sendMessage(): void
    {
        if (trim($this->newMessage) === '') {
            return;
        }

        $this->messages[] = [
            'id' => count($this->messages) + 1,
            'contact_id' => $this->activeChat,
            'sender' => 'me',
            'text' => $this->newMessage,
            'time' => now()->diffForHumans(),
        ];

        $this->newMessage = '';
    }

    public function getActiveContactProperty(): array
    {
        return collect($this->contacts)->firstWhere('id', $this->activeChat) ?? [];
    }

    public function getActiveMessagesProperty(): array
    {
        return collect($this->messages)->where('contact_id', $this->activeChat)->values()->toArray();
    }

    public function render()
    {
        return view('livewire.whatsapp.chat', [
            'contacts' => $this->contacts,
            'activeContact' => $this->activeContact,
            'activeMessages' => $this->activeMessages,
        ]);
    }
}
