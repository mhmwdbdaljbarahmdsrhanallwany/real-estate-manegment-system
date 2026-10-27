<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AccessContext;
use Symfony\Component\HttpFoundation\Response;

class AccessContextMiddleware
{
    /**
     * Handle an incoming request.
     */public function handle(Request $request, Closure $next): Response
{
    $host = strtolower($request->getHost());

    $baseDomain = 'smartestate.com';

    if ($host === $baseDomain) {
        $domain = $baseDomain;
        $subdomain = null;
    } elseif (str_ends_with($host, '.' . $baseDomain)) {
        $subdomain = substr(
            $host,
            0,
            -strlen('.' . $baseDomain)
        );

        // نقبل نطاقًا فرعيًا واحدًا فقط
        if ($subdomain === '' || str_contains($subdomain, '.')) {
            abort(404, 'Access context not found');
        }

        $domain = $baseDomain;
    } else {
        abort(404, 'Access context not found');
    }

   
$context = AccessContext::where('domain', $domain)
->where('subdomain', $subdomain)
->first();

if (!$context) {
abort(404, 'Access context not found');
}

$request->merge(['access_context' => $context]);

app()->instance(AccessContext::class, $context);

// توجيه المؤسسة غير المفعّلة إلى صفحة الانتظار
if (
$context->context_type === 'ORGANIZATION' &&
$context->status !== 'ACTIVE' &&
!$request->routeIs('admin.organization.waiting')
) {
return redirect()->route('admin.organization.waiting');
}

return $next($request);
}
}