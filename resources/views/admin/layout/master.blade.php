<!DOCTYPE html>

<html>

<head>

    {{-- =========================================================
         Dashboard Theme
         ========================================================= --}}
    <script>
        (function() {
            const theme = localStorage.getItem('dashboard-theme') || 'indigo';

            document.documentElement.setAttribute(
                'data-dashboard-theme',
                theme
            );
        })();
    </script>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">


    <title>@yield('title')</title>


    {{-- تصميم الجدول العام --}}
    <link rel="stylesheet" href="{{ asset('assets/agency/componnet_table.css') }}">


    {{-- Font Awesome --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">


    {{-- Bootstrap 4 RTL --}}
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">


    {{-- Ionicons --}}
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


    {{-- Tempusdominus --}}
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">


    {{-- iCheck --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">


    {{-- JQVMap --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">


    {{-- OverlayScrollbars --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">


    {{-- Daterangepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">


    {{-- Summernote --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}">


    {{-- AdminLTE
         مهم: يتم تحميل نسخة واحدة فقط --}}
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/agency/said.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    {{-- Custom RTL --}}
    <link rel="stylesheet" href="{{ asset('assets/dist/css/custom.css') }}">


    {{-- Main Style --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


    {{-- Agency Styles --}}
    <link rel="stylesheet" href="{{ asset('assets/agency/notes/notes.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/agency/document.css') }}">


    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">


    {{-- Boxicons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">


    {{-- المستخدمين --}}
    <link rel="stylesheet" href="{{ asset('assets/users/style.css') }}">


    {{-- الكارد العرض --}}
    <link rel="stylesheet" href="{{ asset('assets/components/style.css') }}">


    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">


    {{-- Toastr --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/agency/notes/notes.css') }}">


</head>


<body class="hold-transition sidebar-mini layout-fixed">


    <div class="wrapper">


        {{-- =====================================================
             Navbar
             ===================================================== --}}
        @include('admin.incloude.header')


        {{-- =====================================================
             Main Sidebar
             ===================================================== --}}
        @include('admin.incloude.sidbar')


        {{-- =====================================================
             Content Wrapper
             ===================================================== --}}
        <div class="content-wrapper">

            <section class="content">

                <div class="container-fluid">

                    @yield('contect')

                </div>

            </section>

        </div>


        {{-- =====================================================
             Footer
             ===================================================== --}}
        @include('admin.incloude.footer')


        {{-- =====================================================
             Control Sidebar
             ===================================================== --}}
        <aside class="control-sidebar control-sidebar-dark">
        </aside>


    </div>


    {{-- =========================================================
         jQuery
         ========================================================= --}}
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>


    {{-- jQuery UI --}}
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>


    {{-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip --}}
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>


    {{-- Bootstrap 4 --}}
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


    {{-- ChartJS --}}
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>


    {{-- Sparkline --}}
    <script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>


    {{-- JQVMap --}}
    <script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.world.js') }}"></script>


    {{-- jQuery Knob Chart --}}
    <script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>


    {{-- Moment --}}
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>


    {{-- Daterangepicker --}}
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>


    {{-- Tempusdominus Bootstrap 4 --}}
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>


    {{-- Summernote --}}
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>


    {{-- OverlayScrollbars --}}
    {{--
    <script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    --}}


    {{-- AdminLTE --}}
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>


    {{-- AdminLTE Demo --}}
    <script src="{{ asset('assets/dist/js/demo.js') }}"></script>


    {{-- SweetAlert2 --}}
    <script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.all.js') }}"></script>


    {{-- Toastr --}}
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>


    {{-- =========================================================
         Toastr
         ========================================================= --}}
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };


        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif


        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif


        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif


        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if ($errors->any())
    @foreach ($errors->all() as $error)
        toastr.error(@json($error));
    @endforeach
@endif


        $(document).ready(function() {


$(document).on('click', '.deleteBtn', function (e) {
    e.preventDefault();
    const button = $(this);
    const url = button.data('url');

    // المسميات الديناميكية
    const entity = button.data('entity') || 'السجل';
    const entityPlural = button.data('entity-plural') || 'السجلات';
    const deleteButton = document.querySelector('.deleteBtn');


    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف ${entity} منطقيًا، ويمكن استعادته لاحقًا.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}'
            },

            success: function (response) {
    if (!response.success) {
        Swal.fire({
            title: 'تعذر حذف ' + entity,
            text: response.message || `لم يتم حذف ${entity}.`,
            icon: 'warning',
            confirmButtonText: 'حسنًا'
        });
        return;
    }

    Swal.fire({
        title: 'تم الحذف',
        text: response.message || `تم حذف ${entity} منطقيًا بنجاح.`,
        icon: 'success',
        confirmButtonText: 'حسنًا'
    }).then(() => {
        location.reload();
    });
},

            error: function (xhr) {
                const response = xhr.responseJSON || {};

                let title = 'تعذر حذف ' + entity;
                let message = response.message || `حدث خطأ أثناء محاولة حذف ${entity}.`;
                let icon = 'error';

                switch (xhr.status) {
                    case 401:
                        title = 'يلزم تسجيل الدخول';
                        message = response.message ||
                            'انتهت جلسة تسجيل الدخول أو يلزم تسجيل الدخول مجددًا.';
                        icon = 'info';
                        break;

                    case 403:
                        title = 'غير مسموح';
                        message = response.message ||
                            `ليست لديك الصلاحية اللازمة لحذف ${entity}.`;
                        icon = 'warning';
                        break;

                    case 404:
                        title = 'السجل غير موجود';
                        message = response.message ||
                            `قد يكون ${entity} حُذف مسبقًا أو لم يعد متاحًا.`;
                        icon = 'warning';
                        break;

                    case 419:
                        title = 'انتهت صلاحية الجلسة';
                        message = response.message ||
                            'يرجى تحديث الصفحة والمحاولة مجددًا.';
                        icon = 'info';
                        break;

                    case 422:
                        title = 'لا يمكن حذف ' + entity;
                        message = response.message ||
                            `تعذر حذف ${entity} بسبب ارتباطه ببيانات أخرى أو لعدم استيفاء شروط الحذف.`;
                        icon = 'warning';
                        break;

                    case 429:
                        title = 'طلبات كثيرة';
                        message = response.message ||
                            'أرسلت طلبات كثيرة خلال فترة قصيرة. يرجى الانتظار ثم المحاولة.';
                        icon = 'warning';
                        break;

                    case 500:
                    case 503:
                        title = 'خطأ في النظام';
                        message = response.message ||
                            `حدث خطأ في النظام أثناء حذف ${entity}. يرجى المحاولة لاحقًا.`;
                        icon = 'error';
                        break;
                }

                Swal.fire({
                    title: title,
                    text: message,
                    icon: icon,
                    confirmButtonText: 'حسنًا'
                });
            }
        });
    });
});
});
        $(document).on('click', '.restoreBtn', function(e) {
            e.preventDefault();
            const button = $(this);
            const url = button.data('url');
            const entity = button.data('entity');
            const entityPlural = button.data('entity-plural');
            const id = button.data('id');

            if (!url || !entity || !entityPlural || !id) {
                console.error('بيانات الاستعادة غير مكتملة.', {
                    url,
                    entity,
                    entityPlural,
                    id
                });

                return;
            }

            Swal.fire({
                title: `هل تريد استعادة ${entity}؟`,
                text: `سيتم إعادة ${entity} إلى قائمة ${entityPlural}.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `نعم، استعادة ${entity}`,
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',

                    data: {
                        _token: '{{ csrf_token() }}'
                    },

                    beforeSend: function() {
                        button.prop('disabled', true);
                    },

                    success: function(response) {
    if (!response.success) {
        Swal.fire({
            title: `تعذرت استعادة ${entity}`,
            text: response.message || 'تعذر إتمام العملية.',
            icon: 'warning',
            confirmButtonText: 'حسنًا'
        });
        return;
    }

    Swal.fire({
        title: 'تمت الاستعادة',
        text: response.message || `تمت استعادة ${entity} بنجاح.`,
        icon: 'success',
        confirmButtonText: 'حسنًا'
    }).then(() => {
        location.reload();
    });
},
                });
            });
        });


        $(document).on('click', '.forceDeleteBtn', function(e) {
            e.preventDefault();

            var button = $(this);
            var id = button.data('id');
            var url = button.data('url');

            // المسميات الديناميكية
            var entity = button.data('entity') || 'السجل';
            var entityPlural = button.data('entity-plural') || 'السجلات';

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف ' + entity + ' نهائيًا، ولن يمكن استعادته.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذف نهائيًا',
                cancelButtonText: 'إلغاء'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },

                    success: function(response) {
                        Swal.fire(
                            'تم الحذف النهائي!',
                            response.message || 'تم حذف ' + entity + ' نهائيًا بنجاح.',
                            'success'
                        );

                        $('tr')
                            .has('.forceDeleteBtn[data-id="' + id + '"]')
                            .remove();
                    },

                    error: function(xhr) {
                        var message = xhr.responseJSON?.message ||
                            'تعذر حذف ' + entity + ' نهائيًا.';

                        Swal.fire(
                            'تعذر الحذف!',
                            message,
                            'error'
                        );
                    }
                });
            });
        });
    </script>

<script src="{{ asset('assets/js/statistics.js') }}"></script>
    @yield('script')


</body>

</html>
