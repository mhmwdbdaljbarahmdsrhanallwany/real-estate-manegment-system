@extends('admin.layout.master')

@section('title', 'لوحة تحكم المؤسسة')

@section('contect')
@include('admin.incloude.agencynavepare')

<div class="container-fluid py-4">

 
  <!-- لوحة المعلومات العامة -->
  <div class="row  mb-4">
    <div class="col-sm-6 col-md-3 mb-3">
      <div class="card text-center  rounded-3">
        <div class="card-body">
          <i class="fas fa-users fa-2x text-primary mb-2"></i>
          <h6>عدد المستخدمين:<samp>{{ $data->max_users }}</samp></h6>
          
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-3">
      <div class="card text-center  rounded-3">
        <div class="card-body">
          <i class="fas fa-university fa-2x text-success mb-2"></i>
          <h6>عدد الفروع:<samp>{{ $data->max_branches }}</samp></h6>
          
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-3">
      <div class="card text-center rounded-3">
        <div class="card-body">
          <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
          <h6>تاريخ انتهاء الاشتراك:<samp >{{ $data->subscription_expiry }}</samp></h6>
          
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-3 mb-3">
      <div class="card text-center  rounded-3">
        <div class="card-body">
          <i class="fas fa-check-circle fa-2x text-info mb-2"></i>
          <h6>حالة المؤسسة:  <span class="badge bg-success">{{ $data->status }}</span></h6>
          
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-gradient-primary text-white fw-bold">
            <i class="fas fa-building me-2"></i> بيانات المؤسسة
            <button class="btn btn-primary btn-sm editagency" data-id="{{ $data->agency_id }}" data-section="agency">   تعديل</button>
          </div>
          <!-- /.card-header -->
          <div class="table-responsive card-body">
            <table class="table table-bordered text-center align-middle" >
              <thead>                  
                <tr >
                  <th >رقم المؤسسة</th>
                  <th>اسم المؤسسة</th>
                  <th>السجل التجاري</th>
                  <th> الرقم الضريبي</th>

                  <th > الرقم الوطني الموحد</th>
                </tr>
              </thead>
              <tbody id="agency_data" >
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          
        </div>
        <!-- /.card -->

        <div class="card">
          <div class="card-header bg-gradient-success text-white fw-bold">
            <i class="fas fa-university me-2"></i> المعلومات البنكية
            <button class="btn btn-primary btn-sm editagency" data-id="{{ $data->agency_id }}" data-section="banke">   تعديل</button>

          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered" style="text-align: center">
              <thead>
                <tr>
                  <th >اسم البنك</th>
                  <th>اسم الحساب</th>
                  <th>رقم الحساب</th>
                  <th >الايبان</th>
                </tr>
              </thead>
              <tbody id="banke_data">
                
           
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-gradient-warning text-dark fw-bold">
            <i class="fas fa-user-tie me-2"></i> الممثل القانوني
            <button class="btn btn-primary btn-sm editagency" data-id="{{ $data->agency_id }}" data-section="legal">   تعديل</button>

          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>اسم الممثل القانوني</th>
                  <th>رقم هوية الممثل القانوني</th>
                  <th>رقم هاتف الممثل القانوني</th>
                  
                </tr>
              </thead>
              <tbody id="legal_data">
                
                
                
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-gradient-info text-white fw-bold">
            <i class="fas fa-phone me-2"></i> بيانات الاتصال
            <button class="btn btn-primary btn-sm editagency" data-id="{{ $data->agency_id }}" data-section="contact">   تعديل</button>

          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive ">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>الهاتف</th>
                  <th>الجوال</th>
                  <th>البريد الالكتروني</th>
                  <th>الموقع الالكتروني</th>
                  <th>العنوان</th>
                  <th>المدينة</th>
                  <th>الحي</th>
                  <th>الرمز البريدي</th>
                  <th>صندوق البريد</th>

                </tr>
              </thead>
              <tbody id="contact_data">
                
               
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-gradient-secondary text-white fw-bold">
            <i class="fas fa-cogs me-2"></i> الاشتراك والإعدادات
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>تاريخ انتهاء الاشتراك</th>
                  <th>حالة الاشتراك</th>
                  <th>تاريخ انتهاء الفترة التجريبية</th>
                  <th>اخر فحص دفع</th>
                  <th>اقصى عدد فروع</th>
                  <th>اقصى عدد مستخدمين</th>
                  <th>اقصى عدد وحدات</th>

                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ $data->subscription_expiry }}</td>
                  <td>{{ $data->subscription_status }}</td>
                  <td>{{ $data->trial_expiry }}</td>
                  <td>{{ $data->last_payment_check }}</td>
                  <td>{{ $data->max_branches }} </td>
                  <td>{{ $data->max_users }}</td>
                  <td>{{ $data->max_units }} </td>

                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div>
 
  <!-- بطاقات البيانات -->
  <div class="row g-4">
    </div><!-- قسم المستندات -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card  rounded-4 border-0">
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-file-alt me-2"></i> المستندات</span>
            {{-- <button class="btn btn-sm btn-success" data-bs-toggle="offcanvas" data-bs-target="#uploadDocsPanel">
              <i class="fas fa-upload"></i> رفع مستند
            </button> --}}
          </div>
          <div class="card-body">
    
            <!-- المستندات العامة -->
            <h6 class="text-primary">المستندات العامة</h6>
            @if(!empty($data->documents_path))
              <ul class="list-group mb-3">
                @foreach($data->documents_path as $index => $doc)
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $doc['original_name'] }}
                    <div>
                      <a href="{{ asset('storage/'.$doc['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">عرض</a>
                      <a href="{{ asset('storage/'.$doc['path']) }}" download class="btn btn-sm btn-outline-success me-1">تحميل</a>
                      <form action="{{ route('admin.agencies.delete', ['id'=>$data->agency_id,'field'=>'documents_path','index'=>$index]) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                      </form>
                    </div>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="text-muted">لا توجد مستندات عامة</p>
            @endif
    
            <!-- رخصة البلدية -->
            <h6 class="text-primary">رخصة البلدية</h6>
            @if(!empty($data->municipality_license))
              <ul class="list-group mb-3">
                @foreach($data->municipality_license as $index => $doc)
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $doc['original_name'] }}
                    <div>
                      <a href="{{ asset('storage/'.$doc['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">عرض</a>
                      <a href="{{ asset('storage/'.$doc['path']) }}" download class="btn btn-sm btn-outline-success me-1">تحميل</a>
                      <form action="{{ route('admin.agencies.delete', ['id'=>$data->agency_id,'field'=>'municipality_license','index'=>$index]) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                      </form>
                    </div>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="text-muted">لا توجد رخصة بلدية مرفوعة</p>
            @endif
    
            <!-- رخصة الوساطة العقارية -->
            <h6 class="text-primary">رخصة الوساطة العقارية</h6>
            @if(!empty($data->real_estate_broker_license))
              <ul class="list-group mb-3">
                @foreach($data->real_estate_broker_license as $index => $doc)
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $doc['original_name'] }}
                    <div>
                      <a href="{{ asset('storage/'.$doc['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">عرض</a>
                      <a href="{{ asset('storage/'.$doc['path']) }}" download class="btn btn-sm btn-outline-success me-1">تحميل</a>
                      <form action="{{ route('admin.agencies.delete', ['id'=>$data->agency_id,'field'=>'real_estate_broker_license','index'=>$index]) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                      </form>
                    </div>
                  </li>
                @endforeach
              </ul>
            @else
              <p class="text-muted">لا توجد رخصة وساطة عقارية مرفوعة</p>
            @endif
    
          </div>
        </div>
      </div>
    </div>
     {{-- //////الموديل --}}
  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
  
        <div class="modal-header">
          <h5 class="modal-title">تعديل البيانات</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <div class="modal-body" id="modalContent">
          <!-- هنا يظهر الفورم -->
        </div>
  
      </div>
    </div>
  </div>
    
    
@endsection

@section('script')
<script>
  $(document).ready(function(){

    let detailUrl="{{ route('admin.agencies.agencydata',$data->agency_id) }}";
    fetchrecords();

    $(document).on('click','.editagency', function(){
  
  let id = $(this).data('id');
  let section = $(this).data('section');

  // افتح المودال
  $('#myModal').modal('show');

  // تحميل الفورم
  $.ajax({
      url: '{{route('admin.agencies.editSection')}}',
      type: 'GET',
      data: {
          id: id,
          section: section
      },

      beforeSend: function(){
          $('#modalContent').html('جاري التحميل...');
      },

      success: function(response){
          $('#modalContent').html(response);
      },

      error: function(xhr, status, error){
          console.log(xhr);
            console.log(xhr.responseText);

        $('#modalContent').html(xhr.responseText);
 }
  });

});


 
  $(document).on('submit', '#dynamicForm', function(e){
    e.preventDefault();

    let formData = $(this).serialize();

    $.ajax({
        url: '{{ route('admin.agencies.updateSection') }}',
        type: 'POST',
        data: formData,

        success: function(response)
        {
            // اغلاق المودال
            $('#myModal').modal('hide');
            if (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'نجاح',
                                text: 'تم تعديل البيانات بنجاح',
                                width: '300px',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }

            // تحديث البيانات (مؤقت)
            fetchrecords()
        },

        error: function(xhr){
            let errors = xhr.responseJSON.errors;

            let errorHtml = '<div class="alert alert-danger">';
            $.each(errors, function(key, value){
                errorHtml += '<p>' + value[0] + '</p>';
            });
            errorHtml += '</div>';
            $('#modalContent .alert').remove(); // حذف القديم
            $('#modalContent').prepend(errorHtml);
        }
    });
   });

   function fetchrecords()
{
    $.ajax({
        url: detailUrl,
        type: 'GET',
        success: function(response)
        {
             // 🔵 جدول المؤسسة
             let agency = response.agency;

             let tr1 = `
           <tr class="text-center">
           <td>${agency.agency_id}</td>
           <td>${agency.agency_name}</td>
           <td>${agency.commercial_registration}</td>
           <td>${agency.tax_number}</td>
           <td>${agency.unified_national_number}</td>
           </tr>
             `;
            $('#agency_data').html(tr1);


           // 🟡 جدول الممثل القانوني
           let legal = response.legal;

          let tr3 = `
          <tr class="text-center">
          <td>${legal.name}</td>
          <td>${legal.id}</td>
          <td>${legal.phone}</td>
          </tr>
           `;
           $('#legal_data').html(tr3);


            // 🟡 جدول  معلومات البنك
            let banke = response.banke;

             let tr4 = `
             <tr class="text-center">
             <td>${banke.bank_name}</td>
             <td>${banke.bank_account_name}</td>
             <td>${banke.bank_account_number}</td>
             <td>${banke.bank_iban}</td>
             </tr>
              `;
           $('#banke_data').html(tr4);
           
            // 🟡 جدول  معلومات الاتصال
            let contact = response.contact;

             let tr5 = `
             <tr class="text-center">
             <td>${contact.phone}</td>
             <td>${contact.mobile}</td>
             <td>${contact.email}</td>
             <td>${contact.website}</td>
             <td>${contact.address}</td>
             <td>${contact.city}</td>
             <td>${contact.district}</td>
             <td>${contact.postal_code}</td>
             <td>${contact.po_box}</td>

             </tr>
              `;
           $('#contact_data').html(tr5);


        }
    });
   }

  });
  </script>
    
@endsection