<?php

namespace App\Http\Livewire\FrontOrganizations\Building;

use Livewire\Component;

class Buildingscreate extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.building.buildingscreate')
        ->layout('front-organizations.layout.master', ['title' => 'إضافة مبنى جديد']);
    }
}
