<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFarmerApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || !$user->isFarmer()) {
            return redirect()->route('login');
        }

        $farmer = $user->farmer;
        if (!$farmer || !$farmer->isApproved()) {
            return redirect()->route('farmer.dashboard')->with('warning', 'Your farmer account is currently ' . ($farmer?->approval_status ?? 'pending') . '. Market listing features will be unlocked once approved by an administrator.');
        }

        return $next($request);
    }
}
