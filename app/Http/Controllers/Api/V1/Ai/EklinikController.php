<?php

namespace App\Http\Controllers\Api\V1\Ai;

use App\Http\Controllers\Controller;
use App\Jobs\Ai\AiPromptWhatsAppJob;
use Illuminate\Http\Request;

class EklinikController extends Controller
{
    public function prompt(Request $request)
    {
        $validated = $request->validate([
            'to' => ['required', 'string'],
            'prompt' => ['required', 'string'],
        ]);

        AiPromptWhatsAppJob::dispatch($validated['to'], $validated['prompt']);

        return response()->json([
            'status' => 'success',
            'message' => '[AI Prompt] Successfully!',
        ]);
    }
}
