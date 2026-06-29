<?php

namespace App\Http\Controllers\Admin\agency;

use App\Http\Controllers\Controller;
use App\Models\admin\Agency\Agencymodel;
use Illuminate\Http\Request;

class controllerdocument extends Controller
{
    public function index()
    {

        $agencyDocument = Agencymodel::select(
            'agency_id', 'agency_name', 'commercial_registration',
            'tax_number', 'municipality_license',
            'real_estate_broker_license', 'status',
        )
            ->where('status', [
            'pending_approval',
            'active',
            'suspended',
        ])->paginate(4);

        return View('admin.agency.documents', [
            'agencyDocument' => $agencyDocument,
        ]);
    }

    public function ajax_searh_agency_document(Request $request)
    {
        $sortOrder = $request->sort ?? 'asc';
        $sortBy = $request->sortBy ?? 'id';
        $query = Agencymodel::select(
            'agency_id', 'agency_name', 'commercial_registration',
            'tax_number', 'municipality_license',
            'real_estate_broker_license', 'status',
        )
            ->whereIn('status', [
            'pending_approval',
            'active',
            'suspended',
        ])->orderBy($sortBy, $sortOrder);

        // فلترة بالاسم
        if ($request->filled('searchagency_name')) {
            $query->where('agency_name', 'like', '%'.$request->searchagency_name.'%');
        }

        // فلترة بالحالة
        if ($request->statusFilter
        && $request->statusFilter
        != 'all') {
            $query->where('status', $request->statusFilter
            );
        }

        // // فلترة بالاشتراك
        // if($request->subscriptionFilter
        // && $request->subscriptionFilter
        // != 'all'){
        //     $query->where('subscription_status', $request->subscriptionFilter
        // );
        // }

        $agencyDocument = $query->paginate(4);
        $agencyDocument->appends($request->all());

        return view('admin.agency.ajax_searh_agency_documents', ['agencyDocument' => $agencyDocument]);
    }

    public function changeStatus(Request $request)
    {

        $request->validate([

            'agency_id' => 'required|exists:agency,agency_id',

            'status' => 'required|in:active,pending_approval,suspended',
        ]);

        $agency = Agencymodel::findOrFail($request->agency_id);
        $message = '';
        switch ($request->status) {
            case 'active':
                $message = 'تم اعتماد المؤسسة بنجاح';
                break;

            case 'pending_approval':
                $message = 'أصبحت المؤسسة الآن قيد المراجعة';
                break;

            case 'suspended':
                $message = 'تم تعليق عملية التوثيق بنجاح';
                break;

            default:
                $message = 'تم تحديث حالة المؤسسة';
                break;
        }

        $agency->status = $request->status;
        $agency->save();

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);

    }
}
