<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct(private FileService $fileService)
    {
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return response('No valid file provided', 400);
        }

        try {
            $uploadedFile = $this->fileService->uploadFile($request->file('file'));
            
            $fileUrl = url("/file/{$uploadedFile->file_hash}");
            $deleteUrl = url("/delete/{$uploadedFile->delete_token}");

            return response($fileUrl, 200)
                ->header('X-Delete', $deleteUrl)
                ->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            return response('Upload failed: ' . $e->getMessage(), 400);
        }
    }

    public function download(string $fileHash)
    {
        $file = $this->fileService->getFile($fileHash);
        
        if (!$file) {
            return response('File not found', 404);
        }

        if (!Storage::exists($file->file_path)) {
            return response('File not found', 404);
        }

        return response()->file(
            Storage::path($file->file_path),
            [
                'Content-Type' => $file->mime_type,
                'Content-Disposition' => 'inline; filename="' . $file->original_name . '"'
            ]
        );
    }

    public function delete(string $deleteToken)
    {
        if ($this->fileService->deleteFile($deleteToken)) {
            return response('File deleted', 200);
        }
        
        return response('File not found', 404);
    }
}