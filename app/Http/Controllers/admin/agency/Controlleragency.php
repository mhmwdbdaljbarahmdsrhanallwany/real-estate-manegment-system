<?php

namespace App\Http\Controllers\Admin\agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\agencyRequest;
use App\Models\admin\Agency\Agencymodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Controlleragency extends Controller
{
    // دالة عرض معلومات جميع المؤسسات الموجودة ضمن قاعدة البيانات
    public function index()
    {

        $data = Agencymodel::query()->paginate(4);

        return view('admin.agency.index', compact(
            'data',

        ));

    }

    public function ajax_search(Request $request)
    {
        $sortOrder = $request->sort ?? 'asc';
        $sortBy = $request->sortBy ?? 'id';
        $query = Agencymodel::query()->orderBy($sortBy, $sortOrder);

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

        // فلترة بالاشتراك
        if ($request->subscriptionFilter
        && $request->subscriptionFilter
        != 'all') {
            $query->where('subscription_status', $request->subscriptionFilter
            );
        }

        $data = $query->paginate(4);
        $data->appends($request->all());

        return view('admin.agency.ajax_search_agency', ['data' => $data]);
    }

    // /////////////////////////دالة انشاء مؤسسة جديدة واضافتها الى قاعدة البيانات
    public function store(agencyRequest $request)
    {
        $dataagency = $request->all();

        // رخصة البلدية (ملفات/صور)
        if ($request->hasFile('municipality_license')) {
            $municipalityFiles = [];
            foreach ($request->file('municipality_license') as $file) {
                $path = $file->store('uploads/licenses/municipality', 'public');
                $municipalityFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                ];
            }
            $dataagency['municipality_license'] = $municipalityFiles;
        }

        // رخصة الوساطة العقارية (ملفات/صور)
        if ($request->hasFile('real_estate_broker_license')) {
            $brokerFiles = [];
            foreach ($request->file('real_estate_broker_license') as $file) {
                $path = $file->store('uploads/licenses/broker', 'public');
                $brokerFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                ];
            }
            $dataagency['real_estate_broker_license'] = $brokerFiles;
        }

        // المستندات العامة (JSON)
        if ($request->hasFile('documents_path')) {
            $filesData = [];
            foreach ($request->file('documents_path') as $file) {
                $path = $file->store('uploads/agencies', 'public');
                $filesData[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                ];
            }
            $dataagency['documents_path'] = $filesData;
        }
        $agency = Agencymodel::create($dataagency);

        return redirect()->route('admin.agencies.index')->with(['success' => "تمت اضافة المؤسسة ($agency->agency_name) بنجاح"]);
    }

    // ///////////////////////////////////////////////////////دالة فنح واجة التعدديل
    public function edit($id)
    {
        $data = Agencymodel::findOrFail($id);
        if (empty($data)) {
            return redirect()->route('admin.agencies.index')->with(['error' => 'عفوا غير قادر للوصول للبيانات المطلوبة']);

        }

        return View('admin.agency.edit', ['data' => $data]);

    }

    public function update(agencyRequest $request, $id)
    {
        $agency = Agencymodel::findOrFail($id);
        if (! $agency) {
            return redirect()->route('admin.agencies.index')->with(['error' => 'عفوا غير قادر للوصول للبيانات المطلوبة']);
        }
        $dataagency = $request->all();
        $municipality_license=false;
        // رخصة البلدية (ملفات/صور)
        if ($request->hasFile('municipality_license')) {
            $municipalityFiles = [];
            foreach ($request->file('municipality_license') as $file) {
                $path = $file->store('uploads/licenses/municipality', 'public');
                $municipalityFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                ];
            }
            $dataagency['municipality_license'] = $municipalityFiles;
            $municipality_license=$agency->municipality_license;
        }
       

        // رخصة الوساطة العقارية (ملفات/صور)
        $real_estate=false;
        if ($request->hasFile('real_estate_broker_license')) {
            $brokerFiles = [];
            foreach ($request->file('real_estate_broker_license') as $file) {
                $path = $file->store('uploads/licenses/broker', 'public');
                $brokerFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                ];
            }
            $dataagency['real_estate_broker_license'] = $brokerFiles;
            $real_estate=$agency->real_estate_broker_license;


        }

        // المستندات العامة (JSON)
        $documents=false;
        if ($request->hasFile('documents_path')) {
            $filesData = [];
            foreach ($request->file('documents_path') as $file) {
                $path = $file->store('uploads/agencies', 'public');
                $filesData[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                ];
            }
            $dataagency['documents_path'] = $filesData;
            $documents=$agency->documents_path;

        }

        $agency->update( $dataagency);
        if($municipality_license){
            $agency->delete_File($municipality_license);
        }
        if($real_estate){
            $agency->delete_File($real_estate);
        }
        if($documents){
            $agency->delete_File($documents);
        }

        return redirect()->route('admin.agencies.index')->with(['success' => "تمت تعديل المؤسسة ($agency->agency_name) بنجاح"]);
    }

    // دالة حذف بيانات مؤسسة من قاعدة البيانات
    public function delete($id)
    {
        $agency = Agencymodel::findOrFail($id);
        if (! $agency) {
            return response()->json(['error' => 'لم يتم العثور على المؤسسة'], 404);
        }

        $agency->delete();

        $agency->deleteFiles();

        return response()->json(['success' => 'تم حذف المؤسسة بنجاح', 'id' => $id]);
    }

    public function deleteFile($id, $field, $index)
    {
        $agency = Agencymodel::findOrFail($id);
        $files = $agency->$field;

        if (isset($files[$index])) {
            // حذف الملف من التخزين
            Storage::disk('public')->delete($files[$index]['path']);

            // حذف من المصفوفة
            unset($files[$index]);
            $agency->$field = array_values($files); // إعادة ترتيب الفهارس
            $agency->save();

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الملف بنجاح',
                'field' => $field,
                'index' => $index,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'الملف غير موجود أو البيانات غير صحيحة',
        ], 404);
    }
}
