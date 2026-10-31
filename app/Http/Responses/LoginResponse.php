<?php

namespace App\Http\Responses;

use App\Models\AccessContext;
use App\Services\AuthorizationService;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function __construct(
        protected AuthorizationService $authorizationService
    ) {
    }

    public function toResponse($request)
    {
        $context = app(AccessContext::class);

        $user = Auth::user();

        $allowed = $user
            && $context instanceof AccessContext
            && $this->authorizationService->canEnterContext(
                $user,
                $context
            );

        if (!$allowed) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'غير مخوّل لك الدخول إلى النظام.',
                ]);
        }

        if ($context->context_type === 'SYSTEM') {
            return redirect()->route('admin.home');
        }

        if ($context->context_type === 'ORGANIZATION') {
            return redirect()->route('organizations.dashboard');
        }

        if ($context->context_type === 'BRANCH') {
            return redirect()->route('admin.home');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withErrors([
                'email' => 'نوع سياق الوصول غير مدعوم.',
            ]);
    }
}