<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeepLinkingController extends Controller
{
    public function android()
    {
        return response(\Storage::disk('public')->get('deep_link_files/assetlinks.json'))
            ->header('Content-Type', ['application/json']);
    }

    public function apple()
    {
        return response(\Storage::disk('public')->get('deep_link_files/apple-app-site-associations.json'))
            ->header('Content-Type', ['application/json']);
    }
}
