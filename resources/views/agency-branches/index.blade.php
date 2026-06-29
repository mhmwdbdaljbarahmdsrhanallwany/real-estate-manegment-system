@extends('admin.layout.master')

@section('title')
    جميع الموسسات
@endsection

@section('contect')
@include('admin.incloude.agencynavepare')


<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>الفروع</h2>
            <p class="text-muted mb-0">
                إدارة فروع المؤسسة
            </p>
        </div>

        <a href="#" class="btn btn-primary">
            + إضافة فرع
        </a>
    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم الفرع</th>
                        <th>المدير</th>
                        <th>عدد الموظفين</th>
                        <th>العقارات</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>1</td>
                        <td>فرع صنعاء</td>
                        <td>محمد أحمد</td>
                        <td>15</td>
                        <td>230</td>
                        <td>
                            <span class="badge bg-success">
                                نشط
                            </span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">
                                عرض
                            </a>

                            <a href="#" class="btn btn-sm btn-warning">
                                تعديل
                            </a>

                            <a href="#" class="btn btn-sm btn-danger">
                                حذف
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>فرع تعز</td>
                        <td>أحمد علي</td>
                        <td>8</td>
                        <td>95</td>
                        <td>
                            <span class="badge bg-success">
                                نشط
                            </span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">
                                عرض
                            </a>

                            <a href="#" class="btn btn-sm btn-warning">
                                تعديل
                            </a>

                            <a href="#" class="btn btn-sm btn-danger">
                                حذف
                            </a>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>






@endsection
