@extends('admin.layout.master')

@section('title')
    جميع الموسسات
@endsection

@section('contect')
@include('admin.incloude.agencynavepare')




<x-constructor-table
title="المؤسسات العقارية"
:filters="[
    'name'=>'ادخل اسم المؤسسة',
    'status'=>['activ'=>'نشطة',
    'loked'=>'غير مفعل'
    ]
    
]"
:sorts="[
    'sortBy' => ['agency_id'=> 'المعرف','agency_name'=> 'اسم المؤسسة' ],
   'sort' => ['asc'=> 'تصاعدي','desc'=> 'تنازلي'],
]"
/>


<x-constructor-form :types="[
                        'agency_name' => [
                            'label' => 'اسم المؤسسة',
                            'name' => 'agency_name',
                            'type'=>'number'
  ]
  ]"

                        />

                        <input 
                        
                        type="text"
                         class=""
                          id="" 
                           name="" placeholder="" value="" step="" 
                           multiple readonly>






@endsection