<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFacultyAccess
{
    /**
     * Handle an incoming request.
     *
     * This middleware checks if the authenticated user has access to the requested faculty data.
     * Super admins can access all faculties, while faculty admins can only access their own faculty.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is not authenticated, let auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Super admin can access everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Faculty admin can only access their own faculty data
        if ($user->isFacultyAdmin()) {
            // Check if route has faculty_id parameter
            $facultyId = $request->route('faculty') 
                ?? $request->route('questionnaire')?->faculty_id
                ?? $request->input('faculty_id')
                ?? $request->input('faculty');

            // If no faculty_id in route, allow (will be filtered in controller)
            if (!$facultyId) {
                return $next($request);
            }

            // Convert to integer if it's a model
            if (is_object($facultyId)) {
                $facultyId = $facultyId->id;
            }

            // Check if user can access this faculty
            if (!$user->canAccessFaculty((int)$facultyId)) {
                abort(403, 'Anda tidak memiliki akses ke data fakultas ini.');
            }
        }

        return $next($request);
    }
}
