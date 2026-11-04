<?php

namespace App\Http\Livewire\FrontOrganizations\Auth;
use App\Models\AccessContext;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Login extends Component
{
    public string $subdomain = '';

    public array $organization = [];

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function mount(string $subdomain): void
    {
        $this->subdomain = $subdomain;

        $context = AccessContext::with('organization')
            ->where('subdomain', $subdomain)
            ->where('domain', config('services.tenant.domain'))
            ->where('context_type', 'ORGANIZATION')
            ->where('status', 'ACTIVE')
            ->firstOrFail();

        $org = $context->organization;

        abort_unless($org, 404);

        $this->organization = [
            'id' => $org->organization_id,
            'name' => $org->name,
            'commercial_name' => $org->commercial_name,
            'display_name' => $org->commercial_name ?: $org->name,
            'logo' => $org->logo
                ? asset('storage/' . ltrim($org->logo, '/'))
                : null,             
        ];
    }
   public function login()
{
    $this->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    $context = app(AccessContext::class);

    abort_unless(
        $context->context_type === 'ORGANIZATION' &&
        $context->subdomain === $this->subdomain &&
        $context->status === 'ACTIVE',
        403
    );

    // التحقق من بيانات تسجيل الدخول
    if (!Auth::guard('web')->attempt([
        'email' => $this->email,
        'password' => $this->password,
    ], $this->remember)) {

        $this->addError(
            'email',
            'البريد الإلكتروني أو كلمة المرور غير صحيحة.'
        );

        return;
    }

    // التحقق من ارتباط المستخدم بالمؤسسة الحالية
    $isAssigned = DB::table('user_assignments')
        ->where('user_id', Auth::id())
        ->where('access_context_id', $context->id)
        ->where('status', 'ACTIVE')
        ->whereNull('deleted_at')
        ->exists();

    if (!$isAssigned) {

        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->addError(
            'email',
            'هذا الحساب غير مرتبط بهذه المؤسسة.'
        );

        return;
    }

    // تجديد الجلسة بعد نجاح تسجيل الدخول
    request()->session()->regenerate();

    return redirect()->route('organizations.dashboard', [
        'subdomain' => $this->subdomain,
    ]);
}

   public function render()
{
    return view('front-organizations.livewire.dashboard.index')
        ->layout('front-organizations.layout.master');
}
    // public function render()
    // {
    //     return view('front-organizations.livewire.auth.login');
    // }
}
