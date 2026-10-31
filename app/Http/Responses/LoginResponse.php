<?php

namespace App\Http\Responses;
use App\Models\AccessContext;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $context = app(AccessContext::class);
      
        $user = Auth::user();

        $assignment = $user?->assignments()
            ->where('access_context_id', $context->id)
            ->where('status', 'ACTIVE')
            ->first();

        if (!$assignment) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'غير مخوّل لك الدخول إلى النظام.',
                ]);
        }

        return redirect()->route('admin.home');
    }
}