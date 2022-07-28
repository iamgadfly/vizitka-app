<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeepLinkingController extends Controller
{
    public function android()
    {
        return \Storage::disk('public')->get('deep_link_files/assetlinks.json');
    }

    public function apple()
    {
        return \Storage::disk('public')->get('deep_link_files/apple-app-site-associations.json');
    }
}
