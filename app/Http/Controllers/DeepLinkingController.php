<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeepLinkingController extends Controller
{
    /**
     * @return Application|ResponseFactory|Response
     */
    public function android(): Application|ResponseFactory|Response
    {
        return response(\Storage::disk('public')->get('deep_link_files/assetlinks.json'))
            ->header('Content-Type', ['application/json']);
    }

    /**
     * @return Application|ResponseFactory|Response
     */
    public function apple(): Application|ResponseFactory|Response
    {
        return response(\Storage::disk('public')->get('deep_link_files/apple-app-site-associations.json'))
            ->header('Content-Type', ['application/json']);
    }
}
