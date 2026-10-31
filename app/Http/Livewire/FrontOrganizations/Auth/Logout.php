<?php

namespace App\Http\Livewire\FrontOrganizations\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{
    public string $subdomain = '';

    public function mount(string $subdomain): void
    {
        $this->subdomain = $subdomain;
    }

    public function logout()
    {
        // الاحتفاظ بالنطاق الفرعي للمؤسسة
        $subdomain = $this->subdomain;

        // تسجيل خروج المستخدم
        Auth::guard('web')->logout();

        // إنهاء الجلسة الحالية
        request()->session()->invalidate();

        // تجديد رمز الحماية
        request()->session()->regenerateToken();

        // العودة إلى مكوّن Login الخاص بالمؤسسة
        return redirect()->route('organizations.login', [
            'subdomain' => $subdomain,
        ]);
    }

    public function render()
    {
        return view('front-organizations.livewire.auth.logout');
    }
}