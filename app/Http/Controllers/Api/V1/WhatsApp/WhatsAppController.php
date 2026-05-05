<?php

namespace App\Http\Controllers\Api\V1\WhatsApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\WhatsApp\WhatsAppSendMessageRequest;
use App\Http\Services\WhatsApp\WhatsAppService;
use App\Models\Message;

class WhatsAppController extends Controller
{
    public function __construct(protected WhatsAppService $whatsAppService) {}

    public function index()
    {
        $data['model'] = Message::all();
        return view('website.index', $data);
    }

    public function chats()
    {
        $data = $this->whatsAppService->chats();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function sendMessage(WhatsAppSendMessageRequest $request)
    {
        $data = $this->whatsAppService->sendMessage($request);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /** WEBHOOK */
    public function webhook(Request $request)
    {
        $data = $this->whatsAppService->webhook($request);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
