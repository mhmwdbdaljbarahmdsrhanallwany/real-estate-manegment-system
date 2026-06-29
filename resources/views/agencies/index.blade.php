@extends('admin.layout.master')

@section('title')
    جميع الموسسات
@endsection

@section('contect')
@include('admin.incloude.agencynavepare')


<div class="container py-4">

    <div class="mb-4">
        <h2>مرحباً بك في مؤسسة التميز العقارية</h2>
        <p class="text-muted">
            لوحة التحكم الرئيسية للمؤسسة
        </p>
    </div>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>الفروع</h5>
                    <h2>12</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>الموظفون</h5>
                    <h2>45</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>العقارات</h5>
                    <h2>386</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>الإعلانات</h5>
                    <h2>78</h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            العمليات السريعة
        </div>

        <div class="card-body">

            <a href="#" class="btn btn-primary">
                إضافة فرع
            </a>

            <a href="#" class="btn btn-success">
                إضافة موظف
            </a>

            <a href="#" class="btn btn-warning">
                إضافة عقار
            </a>

        </div>
    </div>

</div>







@endsection
