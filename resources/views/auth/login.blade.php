@extends('layouts.auth')
@section('title','تسجيل الدخول')
@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center"
         style="min-height:100vh">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="text-primary">
                            نظام إدارة العقارات
                        </h3>
                        <p class="text-muted">
                            تسجيل الدخول إلى لوحة التحكم
                        </p>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label>
                                البريد الإلكتروني
                            </label>
                            <input 
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="example@email.com"
                            value="{{ old('email') }}"
                            required>
                        </div>
                        <div class="form-group mb-3">
                            <label>
                                كلمة المرور
                            </label>
                            <input 
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="********"
                            required>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <input 
                                type="checkbox"
                                name="remember">
                                تذكرني
                            </div>
                            <a href="{{ route('password.request') }}">
                                نسيت كلمة المرور؟
                            </a>
                        </div>
                        <button 
                        type="submit"
                        class="btn btn-primary w-100">
                            تسجيل الدخول
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection