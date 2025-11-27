<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController
{
    /**
     * Stream a file from the `storage/app/public` disk.
     * This allows frontend links that point to `/storage/app/public/...` to work
     * even if the `public/storage` symlink is missing on Windows.
     */
    public function show(Request $request, $path)
    {
        $path = ltrim($path, '/');

        // Prevent path traversal
        if (strpos($path, '..') !== false) {
            abort(400);
        }

        // If the URL includes 'app/public/' (e.g. /storage/app/public/shop_documents/...),
        // strip that prefix so we can look up the file on the 'public' disk.
        if (str_starts_with($path, 'app/public/')) {
            $path = substr($path, strlen('app/public/'));
        }

        // Also support URLs that accidentally include a leading 'storage/' segment
        // e.g. /storage/storage/app/public/... or /storage/shop_documents/...
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            abort(404);
        }

        return $disk->response($path);
    }
}
