<?php

namespace App\Services\Access;

use App\Models\AccessContext;
use App\Models\Admin\Organizations\Organization;

class AccessContextService
{
    /**
     * إنشاء سياق وصول خاص بالمؤسسة.
     */
    public function createOrganizationContext(
        Organization $organization
    ): AccessContext {
        return AccessContext::create([
            'name' => 'Organization ' . $organization->organization_id,
            'context_type' => 'ORGANIZATION',
            'domain' => config('services.tenant.domain'),
            'subdomain' => 'org-' . $organization->organization_id,
            'organization_id' => $organization->organization_id,
           'status' => 'NEW',
        ]);
    }
}
