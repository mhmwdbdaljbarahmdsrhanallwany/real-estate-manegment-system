<?php

namespace App\Http\Livewire\FrontOrganizations\Tenants;

use Livewire\Component;

class TenantsCreate extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.tenants.tenants--create')
        ->layout('front-organizations.layout.master');
    }
}
