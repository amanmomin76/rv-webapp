<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCrmSessionAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->session()->get('crm_user_id');

        if (! is_numeric($userId) || ! User::query()->whereKey((int) $userId)->exists()) {
            $request->session()->forget('crm_user_id');

            return redirect()->route('login');
        }

        return $next($request);
    }
}
