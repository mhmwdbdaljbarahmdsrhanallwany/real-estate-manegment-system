<?php

namespace App\Services\Settings;

use App\Models\Admin\ActionType;

class ActionSettingsService
{
    /**
     * تجهيز بيانات صفحة إدارة الإجراءات.
     */
    public function indexData(): array
    {
        $actionTypes = ActionType::query()
            ->withCount('actions')
            ->with('lastAction')
            ->orderBy('sort_order')
            ->get();
        $statistics = [
            'total' => $actionTypes->count(),
            'system' => $actionTypes->where('is_system', true)->count(),
            'custom' => $actionTypes->where('is_system', false)->count(),
            'active' => $actionTypes->where('is_active', true)->count(),
            'inactive' => $actionTypes->where('is_active', false)->count(),
        ];

        return compact('actionTypes','statistics');
    }
    public function showData(int $id): array
{

    $actionType = ActionType::withCount('actions')->with('lastAction')->findOrFail($id);
    $recentActions = $actionType->actions()->with('user')->latest('created_at')->limit(10)->get();
    return [

        'actionType' => $actionType,

        'recentActions' => $recentActions,

        'tableData' => [

            'columns' => [
                'المستخدم',
                'المؤسسة',
                'العنوان',
                'التاريخ'
            ],

            'fields' => [
                'user.name',
                'organization.name',
                'title',
                'created_at'
            ],

            'rows' => $recentActions,

        ]

    ];


}
    /**
     * تجهيز بيانات صفحة إنشاء إجراء.
     */
    public function createData(): array
    {
        return [];
    }


    /**
     * حفظ إجراء جديد.
     */
    public function store(array $data): ActionType
    {
        return ActionType::create($data);
    }


    /**
     * تجهيز بيانات صفحة التعديل.
     */
    public function editData(int $id): array
    {
        $actionType = ActionType::findOrFail($id);
        return compact('actionType');
    }


    /**
     * تحديث بيانات الإجراء.
     */
    public function update(int $id, array $data): ActionType
    {
        $actionType = ActionType::findOrFail($id);
        $actionType->update($data);
        return $actionType;
    }


    /**
     * حذف إجراء.
     */
    public function destroy(int $id): bool
    {
        $actionType = ActionType::findOrFail($id);
        if ($actionType->is_system) {
            return false;

        }

        return $actionType->delete();
    }
}