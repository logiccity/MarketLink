<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user is active
        if ($user->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account has been ' . $user->status . '. Please contact support.']);
        }

        // Check user role
        if (!in_array($user->role, $roles, true)) {
            // Redirect to appropriate dashboard based on user's actual role
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('error', 'You do not have access to that area.'),
                'farmer' => redirect()->route('farmer.dashboard')->with('error', 'You do not have access to that area.'),
                'customer' => redirect()->route('customer.dashboard')->with('error', 'You do not have access to that area.'),
                default => abort(403, 'Unauthorized access.'),
            };
        }

        return $next($request);
    }
}
