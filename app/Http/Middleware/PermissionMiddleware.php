<?php

namespace App\Http\Middleware;

use App\Models\AccessContext;
use App\Services\AuthorizationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function __construct(
        protected AuthorizationService $authorizationService
    ) {
    }

    /**
     * الاستخدام:
     * permission:properties.create
     */
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response {
        // 1. التحقق من المستخدم الحالي.
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // 2. التأكد من تسجيل سياق الوصول.
        if (!app()->bound(AccessContext::class)) {
            abort(403, 'Access context not found');
        }

        $context = app(AccessContext::class);

        // 3. التأكد من أن السياق كائن صالح.
        if (!$context instanceof AccessContext) {
            abort(403, 'Invalid access context');
        }

        // 4. التحقق من الصلاحية المطلوبة داخل السياق.
        $allowed = $this->authorizationService->can(
            $user,
            $permission,
            $context
        );

        if (!$allowed) {
            abort(
                403,
                'You do not have permission to perform this action'
            );
        }

        // 5. السماح باستكمال الطلب.
        return $next($request);
    }
}
