<?php

namespace App\Http\Middleware;

use App\Models\Blacklist;
use Closure;
use Illuminate\Http\Request;

class EnsureClientIsNotInBlacklist
{
    public function handle(Request $request, Closure $next)
    {
        $blacklisted = Blacklist::where([
            'specialist_id' => $request->id,
            'blacklisted_id' => auth()->user()->client->id
        ])->first();
        if (!is_null($blacklisted)) {
            return response()->json([
                'message' => 'You are in blacklist'
            ], 403);
        }
        return $next($request);
    }
}
