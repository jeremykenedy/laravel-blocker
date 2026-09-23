<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Controllers;

use Illuminate\Routing\Controller;

class BlockerAssetController extends Controller
{
    public function __invoke($asset)
    {
        abort_unless(in_array($asset, ['blocker.css', 'legacy.css', 'blocker.js'], true), 404);

        return response()->file(__DIR__.'/../../../resources/assets/'.$asset, [
            'Content-Type'           => $asset === 'blocker.js' ? 'application/javascript' : 'text/css',
            'Cache-Control'          => 'public, no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
