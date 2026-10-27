@extends('admin.layout.master')

@section('title','الملف الشخصي')

@section('contect')

<style>
    .profile-container {
        max-width: 780px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    .profile-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .profile-cover {
        height: 140px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        position: relative;
    }
    .profile-avatar-section {
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 0 2rem;
        margin-top: -50px;
    }
    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 16px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: 4px solid #fff;
        box-shadow: 0 4px 15px rgba(99,102,241,.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 2.2rem;
        font-weight: 700;
        flex-shrink: 0;
        overflow: hidden;
        position: relative;
    }
    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .profile-avatar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity .2s;
        cursor: pointer;
        border-radius: 12px;
    }
    .profile-avatar:hover .profile-avatar-overlay {
        opacity: 1;
    }
    .profile-avatar-overlay i {
        color: #fff;
        font-size: 1.2rem;
    }
    .profile-name-section {
        padding: 1rem 2rem .5rem;
    }
    .profile-name {
        font-size: 1.4rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: #eef2ff;
        color: #4f46e5;
        padding: .25rem .85rem;
        border-radius: 40px;
        font-size: .8rem;
        font-weight: 500;
        margin-top: .3rem;
    }
    .profile-body {
        padding: .5rem 2rem 2rem;
    }
    .profile-divider {
        border: none;
        border-top: 1px solid #f1f5f9;
        margin: 1rem 0;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 576px) {
        .info-grid { grid-template-columns: 1fr; }
    }
    .info-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
    }
    .info-item-label {
        font-size: .78rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: .3rem;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .info-item-label i {
        width: 14px;
        color: #6366f1;
        font-size: .75rem;
    }
    .info-item-value {
        font-size: .95rem;
        color: #0f172a;
        font-weight: 600;
    }
    .info-item-value .muted {
        color: #94a3b8;
        font-weight: 400;
    }
    .profile-actions {
        padding: 0 2rem 2rem;
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .btn-profile {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .55rem 1.2rem;
        border-radius: 10px;
        font-size: .85rem;
        font-weight: 500;
        text-decoration: none;
        transition: all .15s;
        border: none;
        cursor: pointer;
    }
    .btn-profile-primary {
        background: #4f46e5;
        color: #fff;
    }
    .btn-profile-primary:hover {
        background: #4338ca;
        color: #fff;
        text-decoration: none;
    }
    .btn-profile-outline {
        background: transparent;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    .btn-profile-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        text-decoration: none;
    }
</style>

<div class="profile-container">

    <div class="profile-card">

        <!-- الغلاف -->
        <div class="profile-cover"></div>

        <!-- الصورة والاسم -->
        <div class="profile-avatar-section">
            <div class="profile-avatar mt-3">
                @if(!empty($profile->avatar))
                    <img src="{{ $profile->avatar }}" alt="{{ $profile->full_name }}">
                @else
                    {{ mb_substr($profile->full_name, 0, 1) }}
                @endif
                <div class="profile-avatar-overlay" onclick="document.getElementById('avatar-input').click();">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <form id="avatar-form" action="" method="POST" enctype="multipart/form-data" style="display:none;">
                @csrf
                <input type="file" id="avatar-input" name="avatar" accept="image/*" onchange="document.getElementById('avatar-form').submit();">
            </form>
            <div class="profile-name-section" style="flex:1;">
                <h1 class="profile-name">{{ $profile->full_name }}</h1>
                <span class="profile-role-badge">
                    <i class="fas fa-shield-alt" style="font-size:.7rem;"></i>
                    {{ $currentRole }}
                </span>
            </div>
        </div>

        <!-- معلومات -->
        <div class="profile-body">

            <hr class="profile-divider">

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-item-label">
                        <i class="fas fa-envelope"></i>
                        البريد الإلكتروني
                    </div>
                    <div class="info-item-value" style="direction:ltr;text-align:right;">
                        {{ $user->email }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-label">
                        <i class="fas fa-phone"></i>
                        الهاتف
                    </div>
                    <div class="info-item-value">
                        {{ $profile->phone ?? '<span class="muted">غير محدد</span>' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-label">
                        <i class="fas fa-venus-mars"></i>
                        الجنس
                    </div>
                    <div class="info-item-value">
                        {{ $profile->gender ?? '<span class="muted">غير محدد</span>' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-item-label">
                        <i class="fas fa-cake-candles"></i>
                        تاريخ الميلاد
                    </div>
                    <div class="info-item-value">
                        {{ optional($profile->birth_date)->format('Y-m-d') ?? '<span class="muted">غير محدد</span>' }}
                    </div>
                </div>

            </div>

        </div>

        <!-- الأزرار -->
        <div class="profile-actions">
            <a href="{{ route('admin.profile.edit') }}" class="btn-profile btn-profile-primary">
                <i class="fas fa-edit"></i>
                تعديل البيانات
            </a>
            <a href="" class="btn-profile btn-profile-outline">
                <i class="fas fa-key"></i>
                تغيير كلمة المرور
            </a>
        </div>

    </div>

</div>

@endsection
