<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" name="viewport" >
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/agency/notes/notes.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Bootstrap RTL -->
  <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">

  <!-- Tempusdominus -->
  <link rel="stylesheet"
    href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">

  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

  <!-- Daterangepicker -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">

  <!-- Summernote -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">

  <!-- Custom RTL -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/custom.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  {{-- المستخدمين --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Chart.js للإحصائيات -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="{{ asset('assets/users/style.css')}}">
  <!-- Tailwind CSS + Font Awesome -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Chart.js للإحصائيات -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


  {{-- الكارد العرض --}}

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/components/style.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
   

<link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
   {{--نهايه الكارد العرض  --}}
   
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    @include('admin.incloude.header')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin.incloude.sidbar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid ">

          @yield('contect')

      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('admin.incloude.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 rtl -->
  <script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
  <!-- Sparkline -->
  <script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
  <!-- JQVMap -->
  <script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.world.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->

  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <!-- Summernote -->
  <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  {{-- <script src="{{ asset('assets/dist/js/pages/dashboard.js') }}"></script> --}}
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('assets/dist/js/demo.js') }}"></script>
  <!-- SweetAlert2 -->
  <script src="{{ asset('assets/plugins/sweetalert2/dist/sweetalert2.all.js') }}"></script>

<!-- Toastr -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

<script>




toastr.options = {
  
  "closeButton": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "timeOut": "3000"

};

@if(session('success'))

  toastr.success("{{ session('success') }}");

@endif

@if(session('error'))

  toastr.error("{{ session('error') }}");

@endif

@if(session('warning'))

  toastr.warning("{{ session('warning') }}");

@endif

@if(session('info'))

  toastr.info("{{ session('info') }}");

@endif

  $(document).ready(function(){
    $(document).on('click', '.deleteBtn', function(e){
        e.preventDefault();
  
        var id  = $(this).data('id');
        var url = $(this).data('url');
  
        Swal.fire({
            title: "هل أنت متأكد؟",
            text: "لن تستطيع استرجاع البيانات!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "نعم، احذف!",
            cancelButtonText: "إلغاء"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire("تم الحذف!", response.success, "success");
                        // حذف الصف من الجدول مباشرة
                        $('tr').has('a[data-id="'+id+'"]').remove();
                    },
                    error: function(xhr) {
                        Swal.fire("خطأ!", "فشل الحذف.", "error");
                    }
                });
            }
        });
    });
  });
  </script>
  @yield('script')
</body>

</html>