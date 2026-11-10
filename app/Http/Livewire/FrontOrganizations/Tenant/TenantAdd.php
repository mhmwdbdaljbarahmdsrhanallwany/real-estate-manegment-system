<?php

namespace App\Http\Livewire\FrontOrganizations\Tenant;

use Livewire\Component;

class TenantAdd extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.tenant.tenant-add')
        ->layout('front-organizations.layout.master');
    }
}
