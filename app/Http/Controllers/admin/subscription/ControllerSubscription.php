<?php

namespace App\Http\Controllers\Admin\subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionPlanRequest;
use App\Models\admin\Subscription\SubscriptionModel;

use Illuminate\Http\Request;

class ControllerSubscription extends Controller
{
    public function index()
    {
        $data = SubscriptionModel::query()->paginate(4);
        return View('admin.subscription.index', ['data' => $data]);
    }

    public function ajax_searchsup(Request $request){
        $sortOrder = $request->sort ?? 'asc';
        $sortBy = $request->sortBy ?? 'id';
        $query = SubscriptionModel::query()->orderBy($sortBy,$sortOrder);
    
        // فلترة بالاسم
        if($request->filled('searchsup_name')){
            $query->where('plan_name','like','%'.$request->searchsup_name.'%');
        }
    
        // فلترة بالحالة
        if($request->statusFilter && $request->statusFilter != 'all'){
            $query->where('status', $request->statusFilter);
        }
    
        // فلترة بالاشتراك
        if($request->plan_tierFilter && $request->plan_tierFilter != 'all'){
            $query->where('plan_tier', $request->plan_tierFilter);
        }
    
        $data = $query->paginate(4);
        $data->appends($request->all());
    
        return view('admin.subscription.ajax_serch_subscription', ['data' => $data]);
    }
    


    public function detaile($id)
{
    $plan = SubscriptionModel::findOrFail($id);
    return view('admin.subscription.detaile', compact('plan'));
}


    public function create()
    {
        return View('admin.subscription.create');
    }

    public function store(SubscriptionPlanRequest $request)
    {
        $datasubscription = $request->all();

      

        $subscription=SubscriptionModel::create($datasubscription);

        return redirect()->route('admin.subscription.index')->with(['success' =>"تمت الإضافة ($subscription->plan_name) بنجاح"]);
    }

    public function edit($id)
    {
        $data = SubscriptionModel::findOrFail($id);
        if (empty($data)) {
            return redirect()->route('admin.subscription.index')->with(['error' => 'عفوا غير قادر للوصول للبيانات المطلوبة']);
        }

        return View('admin.subscription.edit', ['data' => $data]);
    }

    public function update(SubscriptionPlanRequest $request,$id)
    {
        $subscription = SubscriptionModel::findOrFail($id);
        if (!$subscription) {
            return redirect()->route('admin.subscription.index')->with(['error' => 'عفوا غير قادر للوصول للبيانات المطلوبة']);
        }
          $datasubscription = $request->all();
      

        $subscription->update($request->all());

        return redirect()->route('admin.subscription.index')->with(['success' =>"تمت تعديل ($subscription->plan_name) بنجاح"]);
    }

    public function delete($id)
    {
       
        $data = SubscriptionModel::findOrFail($id);
        if (!$data) {
            return response()->json(['error' => 'لم يتم العثور على الباقة'], 404);
        }
    
        $data->delete();
        return response()->json(['success' => 'تم حذف الباقة بنجاح', 'id' => $id]);
    }
}
