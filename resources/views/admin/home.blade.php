@extends('admin.layout.master')

@section('title')
الرئيسية
@endsection

@section('contect')

<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #eef2ff;
        --success: #10b981;
        --success-light: #ecfdf5;
        --warning: #f59e0b;
        --warning-light: #fffbeb;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-500: #6b7280;
        --gray-700: #374151;
        --gray-900: #111827;
    }

    .saas-dashboard {
        display: grid;
        gap: 1.5rem;
    }

    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        color: #fff;
        box-shadow: 0 8px 30px rgba(79,70,229,.25);
    }
    .welcome-banner h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0 0 .3rem;
    }
    .welcome-banner .text-muted {
        color: rgba(255,255,255,.8) !important;
        margin: 0;
        font-size: .95rem;
    }

    /* Cards Grid */
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    @media (max-width: 768px) {
        .cards-grid { grid-template-columns: 1fr; }
    }

    /* Card Base */
    .saas-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--gray-100);
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
        overflow: hidden;
        transition: transform .15s, box-shadow .15s;
    }
    .saas-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,.08);
    }

    .saas-card-header {
        padding: 1.1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: .6rem;
        font-size: .95rem;
        font-weight: 600;
        color: #fff;
        border-bottom: 1px solid rgba(255,255,255,.12);
    }
    .saas-card-header i {
        font-size: 1rem;
    }

    .saas-card-body {
        padding: 1.5rem;
    }

    /* User Card */
    .card-user .saas-card-header {
        background: var(--primary);
    }
    .info-row {
        display: flex;
        padding: .75rem 0;
        border-bottom: 1px solid var(--gray-100);
    }
    .info-row:last-child { border-bottom: none; }
    .info-label {
        width: 80px;
        font-size: .85rem;
        color: var(--gray-500);
        flex-shrink: 0;
    }
    .info-value {
        font-size: .9rem;
        color: var(--gray-900);
        font-weight: 500;
    }
    .role-badge {
        display: inline-block;
        background: var(--primary-light);
        color: var(--primary);
        padding: .2rem .85rem;
        border-radius: 40px;
        font-size: .8rem;
        font-weight: 500;
    }

    /* Access Context Card */
    .card-context .saas-card-header {
        background: var(--success);
    }
    .context-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: var(--gray-50);
        border-radius: 40px;
        padding: .35rem 1rem .35rem .8rem;
        font-size: .82rem;
        color: var(--gray-700);
        border: 1px solid var(--gray-200);
        margin: .2rem .15rem;
    }
    .context-chip i {
        color: var(--success);
        font-size: .6rem;
    }

    /* Activity Card */
    .card-activity .saas-card-header {
        background: var(--warning);
        color: #1a1a2e;
    }
    .activity-time {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }
    .activity-label {
        font-size: .85rem;
        color: var(--gray-500);
        margin: 0 0 .2rem;
    }
</style>

<div class="saas-dashboard mt-3  ">

    <!-- الترحيب -->
    <div class="welcome-banner">
        <h3>👋 مرحباً، {{ $user->name }}</h3>
        <p class="text-muted">أهلاً بك في نظام إدارة العقارات</p>
    </div>

    <!-- بطاقات المعلومات -->
    <div class="cards-grid">

        <!-- معلومات المستخدم -->
        <div class="saas-card card-user">
            <div class="saas-card-header">
                <i class="fas fa-user"></i>
                معلومات المستخدم
            </div>
            <div class="saas-card-body">
                <div class="info-row">
                    <span class="info-label">الاسم</span>
                    <span class="info-value">{{ $user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">البريد</span>
                    <span class="info-value" style="direction:ltr;text-align:right;">{{ $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">الدور</span>
                    <span class="info-value">
                        @if($role)
                            <span class="role-badge">{{ $role->display_name }}</span>
                        @else
                            <span class="role-badge" style="background:var(--gray-100);color:var(--gray-500);">غير محدد</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- سياق الوصول -->
        <div class="saas-card card-context">
            <div class="saas-card-header">
                <i class="fas fa-globe"></i>
                سياق الوصول
            </div>
            <div class="saas-card-body">
                @if($context)
                    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.4rem;">
                        <span class="context-chip"><i class="fas fa-circle"></i> {{ $context->name }}</span>
                        <span class="context-chip"><i class="fas fa-tag"></i> {{ $context->context_type }}</span>
                        @if($context->domain)
                            <span class="context-chip"><i class="fas fa-link"></i> {{ $context->domain }}</span>
                        @endif
                    </div>
                @else
                    <div style="text-align:center;padding:.5rem 0;color:var(--gray-500);font-size:.9rem;">
                        <i class="fas fa-minus-circle" style="margin-left:.4rem;"></i>
                        لا يوجد سياق وصول
                    </div>
                @endif
            </div>
        </div>

        <!-- آخر نشاط -->
        <div class="saas-card card-activity">
            <div class="saas-card-header">
                <i class="fas fa-clock"></i>
                آخر نشاط
            </div>
            <div class="saas-card-body" style="text-align:center;padding:1.8rem 1.5rem;">
                <p class="activity-label">آخر تحديث للحساب</p>
                <p class="activity-time">
                    <i class="far fa-calendar-alt" style="margin-left:.5rem;color:var(--warning);"></i>
                    {{ $user->updated_at->format('Y-m-d H:i') }}
                </p>
            </div>
        </div>

    </div>

</div>

@endsection
