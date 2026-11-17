<?php

namespace App\Http\Livewire\FrontOrganizations\Tenants\TenantsResidents;

use Livewire\Component;

class TenantsResidents extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.tenants.tenants-residents.tenants-residents')->layout('front-organizations.layout.master');
    }
}
