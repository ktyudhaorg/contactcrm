<?php

namespace App\Http\Controllers\Proxy\GoogleDrive;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Integrations\IntegrationGoogleDriveService;

class GoogleDriveController extends Controller
{

    public function __construct(protected IntegrationGoogleDriveService $googleDriveService) {}

    public function proxy(Request $request)
    {
        $cloudPath = $request->query('path');
        $fileName   = basename($cloudPath);

        $file = $this->googleDriveService->download($cloudPath);

        return response(base64_decode($file['file']), 200)
            ->header('Content-Type', $file['ext'])
            ->header('Content-Disposition', "inline; filename=\"{$fileName}\"");
    }

    public function download(Request $request)
    {
        $cloudPath = $request->query('path');
        $fileName   = basename($cloudPath);

        $file = $this->googleDriveService->download($cloudPath);

        return response(base64_decode($file['file']), 200)
            ->header('Content-Type', $file['ext'])
            ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"");
    }
}
