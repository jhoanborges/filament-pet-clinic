<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

class AssignGlobalScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Filament::auth()->user();

        Schedule::addGlobalScope(function (Builder $query) {
            $query->whereBelongsTo(Filament::auth()->user(), 'owner');
        });
        /*Appointment::addGlobalScope(function (Builder $query) {
            $query->whereBelongsTo(Filament::auth()->user(), 'doctor');
        });
        */

        // Apply Appointment scope for doctors
        Appointment::addGlobalScope('doctorFilter', function (Builder $query) use ($user) {
            if ($user && $user->role) {
                if ($user->role->name === 'doctor') {
                    $query->where('doctor_id', $user->id);
                } elseif ($user->role->name === 'admin') {
                    // No filtering for admins, they see all
                }
            }
        });

        return $next($request);
    }
}
