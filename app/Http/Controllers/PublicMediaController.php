<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicMediaController extends Controller
{
    // Tampilkan detail data yang dipilih.
    public function show(string $path): BinaryFileResponse
    {
        $path = trim(str_replace('\\', '/', $path), '/');

        abort_if($path === '' || str_contains($path, '../') || str_contains($path, "\0"), 404);

        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        return response()->file($disk->path($path), [
            'Cache-Control' => 'public, max-age=86400',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
