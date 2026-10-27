<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    
    /**
     * عرض الملف الشخصي.
     */
    public function show(): View
    {
        $user = auth()->user()->load([
            'profile',
            'assignments.roles',
        ]);
        $profile = $user->profile;
        $currentRole = optional(
            $user->assignments
                ->first()?->roles
                ->first()
        )->display_name;
        return view('admin.profile.show', compact(
            'user',
            'profile',
            'currentRole'
        ));
    }

    /**
     * صفحة تعديل الملف الشخصي.
     */
    public function edit(): View
    {
        $user = auth()->user();
        $data = $user->profile;
        return view(
            'admin.profile.edit',
            compact('data')
        );
    }

    /**
     * حفظ التعديلات.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100'
            ],
            'last_name' => [
                'required',
                'string',
                'max:100'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30'
            ],
    
            'alternate_phone' => [
                'nullable',
                'string',
                'max:30'
            ],
            'gender' => [
                'nullable',
                'in:Male,Female'
            ],
            'birth_date' => [
                'nullable',
                'date'
            ],
    
        ]);
        $user->profile()->update($validated);
        return redirect()
            ->route('admin.profile.show')
            ->with('success','تم تحديث البيانات بنجاح');
    }
}
