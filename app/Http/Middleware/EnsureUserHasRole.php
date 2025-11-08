<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * 
     * This middleware combines authentication and role-based authorization.
     * It can be used with or without role parameter:
     * - Without role: Just checks authentication
     * - With role: Checks authentication + specific role
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null $role The required role (optional)
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        // Skip role check untuk route Livewire file preview/upload
        if ($request->is('livewire/preview-file/*') || $request->is('livewire/upload-file')) {
            return $next($request);
        }

        // Log to ensure middleware is invoked
        Log::info('EnsureUserHasRole middleware triggered', [
            'requested_url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Log authentication status for debugging
        Log::debug('EnsureUserHasRole middleware invoked', [
            'is_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'user_email' => Auth::check() ? Auth::user()->email : null,
            'requested_url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        // First, check if user is authenticated
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                    'error' => 'Authentication required to access this resource.'
                ], 401);
            }
            
            return redirect()->route('login')
                ->with('message', 'Silakan login terlebih dahulu untuk mengakses halaman ini.')
                ->with('intended_url', $request->fullUrl());
        }

        

        // If role is specified, check if user has the required role
        if ($role !== null) {
            $userRole = Auth::user()->role;
            
            // Support multiple roles separated by pipe (|)
            $allowedRoles = explode('|', $role);
            
            if (!in_array($userRole, $allowedRoles)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Access denied. Insufficient permissions.',
                        'error' => "Required role: {$role}. Current role: {$userRole}",
                        'required_roles' => $allowedRoles,
                        'current_role' => $userRole
                    ], 403);
                }
                
                // Log unauthorized access attempt
                Log::warning('Unauthorized access attempt', [
                    'user_id' => Auth::id(),
                    'user_email' => Auth::user()->email,
                    'user_role' => $userRole,
                    'required_role' => $role,
                    'requested_url' => $request->fullUrl(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                
                return redirect('/')
                    ->with('error', 'Anda tidak memiliki akses ke halaman ini. Role yang dibutuhkan: ' . implode(' atau ', $allowedRoles))
                    ->with('required_role', $allowedRoles)
                    ->with('current_role', $userRole);
            }
        }

        // User is authenticated and (if role specified) has correct role
        return $next($request);
    }

    /**
     * Get a descriptive name for this middleware
     */
    public static function name(): string
    {
        return 'Role-based Authentication & Authorization Middleware';
    }

    /**
     * Get supported roles from User model enum
     */
    public static function getSupportedRoles(): array
    {
        return ['jemaat', 'pengurus', 'pendeta'];
    }
}
