<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckHumanFactorQuestionnaireCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Make sure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Get current user data
        $user = Auth::user();

        // Check if the user ID exists in the pivot table
        $exists = DB::table('human_factor_user')->where('user_id', $user->id)->exists();

        if (!$exists) {
            if ($request->routeIs('hf.questionnaire') || $request->routeIs('hf.questionnaire.store')) {
                return $next($request);
            }else{
                return redirect()->intended(route('hf.questionnaire', absolute: true));
            }
        }else{
            if ($request->routeIs('hf.questionnaire') || $request->routeIs('hf.questionnaire.store')) {
                return redirect()->to(url()->previous());
            }else{
                return $next($request);
            }
        }
        return $next($request);
    }
}
