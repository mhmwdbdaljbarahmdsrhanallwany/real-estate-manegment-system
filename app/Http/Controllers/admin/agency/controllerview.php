<?php

namespace App\Http\Controllers\Admin\agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\agencyRequest;
use App\Models\admin\Agency\Agencymodel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class controllerview extends Controller
{
    public function detaile($id)
    {
        $data=Agencymodel::findOrFail($id);
        if(empty($data)){
            return redirect()->route('admin.agencies.index')->with(['error'=>'عفوا غير قادر للوصول للبيانات المطلوبة']);

        }
        return view('admin.agency.detaile',['data'=>$data]);
    }


    public function agencydata($id)
{
    $data = Agencymodel::findOrFail($id);

    if (!$data) {
        return response()->json(['error' => 'not found'], 404);
    }

    return response()->json([
        'agency' => [
            'agency_id' => $data->agency_id,
            'agency_name' => $data->agency_name,
            'commercial_registration' => $data->commercial_registration,
            'tax_number' => $data->tax_number,
            'unified_national_number' => $data->unified_national_number,
        ],
        'legal' => [
            'name' => $data->legal_representative_name,
            'id' => $data->legal_representative_id,
            'phone' => $data->legal_representative_phone,
        ],

        'banke' => [
            'bank_name' => $data->bank_name,
            'bank_account_name' => $data->bank_account_name,
            'bank_account_number' => $data->bank_account_number,
            'bank_iban' => $data->bank_iban,

        ],
        'contact' => [
            'phone' => $data->phone,
            'mobile' => $data->mobile,
            'email' => $data->email,
            'website' => $data->website,
            'address' => $data->address,
            'city' => $data->city,
            'district' => $data->district,
            'postal_code' => $data->postal_code,
            'po_box' => $data->po_box,


        ],




    ]);
}



public function editSection(Request $request)
{    
    $data = Agencymodel::findOrFail($request->id)->first();

    if (!$data) {
        return response()->json(['error' => 'البيانات غير موجودة'], 404);
    }

    switch ($request->section) {

        case 'agency':
            return view('admin.model_detaile.agency', compact('data'));

        case 'banke':
            return view('admin.model_detaile.banke', compact('data'));

        case 'legal':
            return view('admin.model_detaile.legal', compact('data'));

        case 'contact':
            return view('admin.model_detaile.contact', compact('data'));

        default:
            return response()->json(['error' => 'القسم غير صالح'], 400);
    }
}

    public function updateSection(Request $request)
    {
        $agency = Agencymodel::findOrFail($request->id);
        //التحقق من عدم تكرار هذه الحقول في باقي المؤسسات

       
        switch ($request->section) {

            case 'agency':
                $request->validate([

                    'commercial_registration' => [
                        'required',
                        Rule::unique('agency', 'commercial_registration')->ignore($request->id, 'agency_id'),
                    ],
                    'tax_number' => [
                        'required',
                        Rule::unique('agency', 'tax_number')->ignore($request->id, 'agency_id'),
                    ],
                ]);
                $agency->update($request->only([
                    'agency_name',
                    'commercial_registration',
                    'tax_number',
                    'unified_national_number',
                ]));
                break;
    
            case 'legal':
                $agency->update($request->only([
                    'legal_representative_name',
                    'legal_representative_id',
                    'legal_representative_phone',
                ]));
                break;
    
            case 'banke':
                $agency->update($request->only([
                    'bank_name',
                    'bank_account_name',
                    'bank_account_number',
                    'bank_iban',

                ]));
                break;
    
            case 'contact':
                $agency->update($request->only([
                    'phone',
                    'mobile',
                    'email',
                    'website',
                    'address',
                    'city',
                    'district',
                    'postal_code',
                    'po_box',


                ]));
                break;
    
            default:
                return response()->json(['error' => 'Section غير صالح'], 400);
        }
    
    
        return response()->json(['success' => true,'data' => $agency]);
    }

   
}
