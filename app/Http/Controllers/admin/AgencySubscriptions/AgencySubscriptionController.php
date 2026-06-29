<?php

namespace App\Http\Controllers\Admin\AgencySubscriptions;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\admin\Agency\Agencymodel;
use App\Models\admin\AgencySubscription\AgencySubscription;
use App\Models\admin\Subscription\SubscriptionModel;
use Illuminate\Http\Request;

class AgencySubscriptionController extends Controller
{
    public function index()
    {
        $data = AgencySubscription::with('agency:agency_id,agency_name', 'plan:plan_id,plan_name')->paginate(3);

        return View('admin.agency_subscription.index', ['data' => $data]);

    }

    public function ajax_search_ag_sup(Request $request)
    {
        $sortOrder = $request->sort ?? 'asc';
        $sortBy = $request->sortBy ?? 'id';
        $query = AgencySubscription::query()->orderBy($sortBy, $sortOrder);

        if ($sortBy == 'agency_name') {

            $query->join(
                'agency',
                'agency.agency_id',
                '=',
                'agency_subscriptions.AgencyID'
            )
                ->orderBy('agency.agency_name', $sortOrder)
                ->select('agency_subscriptions.*');

        } else {

            $query->orderBy($sortBy, $sortOrder);

        }

        // فلترة بالاسم
        if ($request->filled('searchSubscription')) {

            $query->whereHas('agency', function ($q) use ($request) {

                $q->where('agency_name', 'like', '%'.$request->searchSubscription.'%');

            });

        }

        // فلترة بالحالة
        if ($request->planStatusFilter && $request->planStatusFilter != 'all') {
            $query->where('PlanStatus', $request->planStatusFilter);
        }

        // فلترة بالاشتراك
        if ($request->subscriptionTypeFilter && $request->subscriptionTypeFilter != 'all') {
            $query->where('SubscriptionType', $request->subscriptionTypeFilter);
        }

        $data = $query->paginate(3);
        $data->appends($request->all());

        return view('admin.agency_subscription.ajax_serch_ag_sup', ['data' => $data]);
    }

    /**
     * عرض تفاصيل اشتراك المؤسسة
     */
    public function detaile($id)
    {
        // جلب الاشتراك مع العلاقات المرتبطة (المؤسسة والباقة)
        $subscription = AgencySubscription::with('agency:agency_id,agency_name', 'plan:plan_id,plan_name')->findOrFail($id);

        // تمرير البيانات إلى واجهة العرض
        return view('admin.agency_subscription.detaile', compact('subscription'));
    }

    public function create()
    {
        $agency = Agencymodel::select('agency_id', 'agency_name')->where('status', 'active')->get();
        $subscription = SubscriptionModel::select('plan_id', 'plan_name', 'duration', 'plan_price', 'tax_rate', 'discount_rate', 'trial_period_value', 'trial_period_unit', 'late_fee', 'grace_period', 'subscription_type')
            ->where('status', 'active')->get();

        return View('admin.agency_subscription.create', ['agency' => $agency, 'subscription' => $subscription]);
    }

    public function store(SubscriptionRequest $request)
    {
        $dataAgencySubscription = $request->all();
        // بيانات المؤسسة
      

      $AgencySubscription= AgencySubscription::create($dataAgencySubscription);

      $agency = $AgencySubscription->agency;

      // جلب الباقة عبر العلاقة
      $plan = $AgencySubscription->plan;
  
      // تحديث بيانات المؤسسة
      $agency->update([
  
          'subscription_expiry' => $request->EndDate,
  
          'trial_expiry' => date('Y-m-d',strtotime($request->EndDate . ' + ' . $request->TrialPeriod . ' days')),
  
          'max_users' => $plan->max_users,
  
          'max_branches' => $plan->max_branches,
  
      ]);
  

       

        return redirect()->route('admin.agencysubscription.index')->with(['success' => 'تم اضافة الاشتراك  بنجاح']);

    }

    public function edit($id)
    {

        $agency = Agencymodel::select('agency_id', 'agency_name')->get();
        $subscription = SubscriptionModel::select('plan_id', 'plan_name', 'duration', 'plan_price', 'tax_rate', 'discount_rate', 'trial_period_value', 'trial_period_unit', 'late_fee', 'grace_period', 'subscription_type')
            ->where('status', 'active')->get();
        $dataSupAgency = AgencySubscription::with(['agency', 'plan'])->find($id);

        return View('admin.agency_subscription.edit', ['agency' => $agency, 'subscription' => $subscription, 'dataSupAgency' => $dataSupAgency]);
    }

    public function update(SubscriptionRequest $request,$id )
    {

        $dataAgencySubscription = AgencySubscription::findOrFail($id);
        if (! $dataAgencySubscription) {
            return redirect()->route('admin.agencysubscription.index')->with(['error' => 'عفوا غير قادر للوصول للبيانات المطلوبة']);
        }
        $AgencySubscription=$request->all();
       
        $dataAgencySubscription->update($AgencySubscription);
        $dataAgencySubscription->load(['agency', 'plan']);

        $agency = $dataAgencySubscription->agency;

        // جلب الباقة عبر العلاقة
        $plan = $dataAgencySubscription->plan;
    
        // تحديث بيانات المؤسسة
        $agency->update([
    
            'subscription_expiry' => $request->EndDate,
    
            'trial_expiry' => date('Y-m-d',strtotime($request->EndDate . ' + ' . $request->TrialPeriod . ' days')),
    
            'max_users' => $plan->max_users,
    
            'max_branches' => $plan->max_branches,
    
        ]);

        return redirect()->route('admin.agencysubscription.index')->with(['success' => 'تم تعديل الاشتراك   بنجاح']);

    }

    public function delete($id)
    {

        $data = AgencySubscription::findOrFail($id);
        if (! $data) {
            return response()->json(['error' => 'لم يتم العثور على الاشتراك'], 404);
        }

        $data->delete();

        return response()->json(['success' => 'تم حذف الاشتراك بنجاح', 'id' => $id]);
    }

    public function subscription_entitie()
    {

        $data = AgencySubscription::with(
            ['agency' => function ($data) {

                $data->select('agency_id', 'agency_name');

            },

                'plan' => function ($data) {
                    $data->select('plan_id', 'plan_name');
                },

            ])->paginate(2);

        return View('admin.agency_subscription.subscription_entitie.subscription_entities', ['data' => $data]);

    }

    public function serch1(Request $request)
    {

        $sortOrder = $request->sort ?? 'asc';
        $sortBy = $request->sortBy ?? 'AgencySubscriptionID';

        $query = AgencySubscription::query();

        if ($sortBy == 'plan_name') {

            $query->orderBy(
                SubscriptionModel::select('plan_name')
                    ->whereColumn('plan.plan_id', 'agency_subscriptions.PlanID'),
                $sortOrder
            );

        } else {

            $query->orderBy($sortBy, $sortOrder);
        }

        if ($request->filled('searchsup_name')) {
            $query->whereHas('plan', fn ($q) => $q->where('plan_name', 'like', '%'.$request->searchsup_name.'%')
            );
        }

        // فلترة بالحالة
        if ($request->planStatusFilter && $request->planStatusFilter != 'all') {
            $query->where('PlanStatus', $request->planStatusFilter);
        }

        // فلترة بالاشتراك
        if ($request->subscriptionTypeFilter && $request->subscriptionTypeFilter != 'all') {
            $query->where('SubscriptionType', $request->subscriptionTypeFilter);
        }

        $data = $query->with([

            'plan' => function ($data) {
                $data->select('plan_id', 'plan_name');
            },

        ])->paginate(2);

        $data->appends($request->all());

        return view('admin.agency_subscription.subscription_entitie.ajax_serch_plan', compact('data'))->render();
    }

    public function serch2(Request $request)
    {

        $sortOrder = $request->sort ?? 'asc';
        $sortBy = $request->sortBy ?? 'AgencySubscriptionID';

        $query = AgencySubscription::query();

        if ($sortBy == 'agency_name') {

            $query->orderBy(
                Agencymodel::select('agency_name')
                    ->whereColumn('agency.agency_id', 'agency_subscriptions.AgencyID'),
                $sortOrder
            );

        } else {

            $query->orderBy($sortBy, $sortOrder);
        }

        if ($request->filled('searchSubscription')) {
            $query->whereHas('agency', fn ($q) => $q->where('agency_name', 'like', '%'.$request->searchSubscription.'%')
            );
        }

        // فلترة بالحالة
        if ($request->planStatusFilter && $request->planStatusFilter != 'all') {
            $query->where('PlanStatus', $request->planStatusFilter);
        }

        // فلترة بالاشتراك
        if ($request->subscriptionTypeFilter && $request->subscriptionTypeFilter != 'all') {
            $query->where('SubscriptionType', $request->subscriptionTypeFilter);
        }

        $data = $query->with([

            'agency' => function ($data) {
                $data->select('agency_id', 'agency_name');
            },

        ])->paginate(2);

        $data->appends($request->all());

        return view('admin.agency_subscription.subscription_entitie.ajax_serch_agency', compact('data'))->render();

    }
}
