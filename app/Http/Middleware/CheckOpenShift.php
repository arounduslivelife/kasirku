<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOpenShift
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for shift management routes themselves to avoid infinite loop
        if ($request->is('shift/*')) {
            return $next($request);
        }

        $activeShift = Shift::where('user_id', Auth::id())->where('status', 'open')->first();

        if (!$activeShift) {
            return redirect()->route('shift.open')->with('warning', 'Silakan buka shift kasir terlebih dahulu untuk melakukan transaksi.');
        }

        return $next($request);
    }
}
