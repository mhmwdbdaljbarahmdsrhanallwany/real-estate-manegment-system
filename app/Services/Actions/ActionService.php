<?php

namespace App\Services\Actions;

use App\Models\Admin\Action;
use App\Models\Admin\ActionType;
use App\Models\Admin\AuditLog;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActionService
{
    /**
     * تسجيل إجراء جديد.
     * نحافظ على هذه الدالة للتوافق مع الاستخدامات الحالية.
     */
    public function log(array $data): Action
    {
        if (empty($data['code'])) {
            throw new \InvalidArgumentException(
                'Action code is required.'
            );
        }

        return DB::transaction(function () use ($data) {
            $type = $this->getActionType($data['code']);

            $action = $this->createAction($type, $data);

            if ($type->write_audit_log) {
                $this->createAuditLog($action, $data);
            }

            return $action;
        });
    }

    /**
     * تسجيل حدث محدد مرة واحدة لكل مفتاح فريد.
     *
     * مثال:
     * subscription:25:subscription_entered_grace
     *
     * يمكن تمرير event_key مخصص للأحداث المتكررة
     * مثل التسويات المختلفة.
     */
    public function logOnceForSubscription(
        string $code,
        int $subscriptionId,
        array $data = []
    ): ?Action {
        $type = $this->getActionType($code);

        $eventKey = $data['event_key']
            ?? "subscription:{$subscriptionId}:{$code}";

        try {
            return DB::transaction(function () use (
                $type,
                $code,
                $subscriptionId,
                $data,
                $eventKey
            ) {
                $existingAction = Action::where(
                    'event_key',
                    $eventKey
                )->first();

                if ($existingAction) {
                    return null;
                }

                $data['code'] = $code;
                $data['subject_type'] = 'subscription';
                $data['subject_id'] = $subscriptionId;
                $data['event_key'] = $eventKey;

                $action = $this->createAction($type, $data);

                if ($type->write_audit_log) {
                    $this->createAuditLog($action, $data);
                }

                return $action;
            });
        } catch (QueryException $exception) {
            // إذا أنشأت عملية متزامنة الحدث نفسه أولاً،
            // فالمفتاح الفريد يمنع إنشاء نسخة ثانية.
            $existingAction = Action::where(
                'event_key',
                $eventKey
            )->first();

            if ($existingAction) {
                return null;
            }

            throw $exception;
        }
    }

    /**
     * جلب نوع الإجراء النشط.
     */
    protected function getActionType(string $code): ActionType
    {
        return ActionType::where('code', $code)
            ->where('is_active', true)
            ->firstOrFail();
    }

    /**
     * إنشاء سجل الإجراء الأساسي.
     */
    protected function createAction(
        ActionType $type,
        array $data
    ): Action {
        return Action::create([
            'action_type_id' => $type->action_type_id,
            'organization_id' => $data['organization_id'] ?? null,
            'user_id' => Auth::id(),
            'subject_type' => $data['subject_type'] ?? null,
            'subject_id' => $data['subject_id'] ?? null,
            'title' => $data['title'] ?? $type->name,
            'description' => $data['description'] ?? null,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'metadata' => $data['metadata'] ?? null,
            'event_key' => $data['event_key'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * إنشاء سجل التدقيق المرتبط بالإجراء.
     */
    protected function createAuditLog(
        Action $action,
        array $data
    ): AuditLog {
        return AuditLog::create([
            'action_id' => $action->action_id,
            'user_id' => Auth::id(),
            'action' => $action->title,
            'model_type' => $data['subject_type'] ?? null,
            'model_id' => $data['subject_id'] ?? null,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
