<?php

namespace App\Http\Livewire\FrontOrganizations\Tenants;

use Livewire\Component;

class TenantsIndex extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.tenants.tenants-index')->layout('front-organizations.layout.master');
    }
}
