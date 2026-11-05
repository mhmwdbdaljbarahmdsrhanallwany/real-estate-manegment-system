<?php
namespace App\Services\Access;
use App\Models\AccessContext;
use App\Models\UserAssignment;
use App\Services\Access;
use Illuminate\Auth\Access\AuthorizationException;

class OrganizationContextService
{
    /**
     * إحضار سياق المؤسسة الحالي والتحقق من صلاحيته.
     */
    public function currentContext(): AccessContext
    {
        // التأكد من أن Middleware سجل سياق الوصول
        if (!app()->bound(AccessContext::class)) {
            throw new AuthorizationException(
                'لم يتم تحديد سياق الوصول.'
            );
        }

        // أخذ السياق المسجل في حاوية Laravel
        $boundContext = app(AccessContext::class);

        // إعادة جلب السياق من قاعدة البيانات للتحقق من حالته الحالية
        $context = AccessContext::query()
            ->find($boundContext->id);

        if (!$context) {
            throw new AuthorizationException(
                'سياق الوصول غير موجود.'
            );
        }

        // السماح بسياق المؤسسات فقط
        if ($context->context_type !== 'ORGANIZATION') {
            throw new AuthorizationException(
                'هذا السياق ليس سياق مؤسسة.'
            );
        }

        // التحقق من تفعيل السياق
        if ($context->status !== 'ACTIVE') {
            throw new AuthorizationException(
                'المؤسسة غير مفعلة.'
            );
        }

        // التحقق من ارتباط السياق بمؤسسة
        if (!$context->organization_id) {
            throw new AuthorizationException(
                'سياق الوصول غير مرتبط بمؤسسة.'
            );
        }

        return $context;
    }

    /**
     * إحضار تعيين المستخدم الحالي داخل المؤسسة.
     */
    public function currentAssignment(): UserAssignment
    {
        $user = auth()->user();

        if (!$user) {
            throw new AuthorizationException(
                'يجب تسجيل الدخول أولاً.'
            );
        }

        $context = $this->currentContext();

        $today = now()->toDateString();

        $assignment = UserAssignment::query()
            ->where('user_id', $user->id)
            ->where('access_context_id', $context->id)
            ->where('status', 'ACTIVE')

            // التحقق من تاريخ بداية التعيين
            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', $today);
            })

            // التحقق من تاريخ نهاية التعيين
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $today);
            })

            ->first();

        if (!$assignment) {
            throw new AuthorizationException(
                'ليس لديك تعيين فعال في هذه المؤسسة.'
            );
        }

        return $assignment;
    }

    /**
     * إحضار رقم المؤسسة الحالية بعد التحقق من المستخدم.
     */
    public function currentOrganizationId(): int
    {
        $this->currentAssignment();

        return (int) $this->currentContext()->organization_id;
    }

    /**
     * التحقق من أن المؤسسة المرسلة هي المؤسسة الحالية.
     */
    public function ensureOrganizationId(
        mixed $organizationId
    ): void {
        $currentOrganizationId = $this->currentOrganizationId();

        if (
            !is_numeric($organizationId) ||
            (int) $organizationId !== $currentOrganizationId
        ) {
            throw new AuthorizationException(
                'لا يسمح لك بالوصول إلى بيانات هذه المؤسسة.'
            );
        }
    }

    /**
     * التحقق من أن سجل قاعدة البيانات يتبع المؤسسة الحالية.
     */
    public function ensureRecordBelongsToOrganization(
        mixed $recordOrganizationId
    ): void {
        $this->ensureOrganizationId($recordOrganizationId);
    }
}
