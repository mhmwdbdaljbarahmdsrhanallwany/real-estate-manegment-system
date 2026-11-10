<?php

namespace App\Http\Livewire\FrontOrganizations\Building;

use Livewire\Component;

class PropertyMap extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.Building.property-map')
        ->layout('front-organizations.layout.master', ['title' => 'الخريطة العقارية']);
    }
}
