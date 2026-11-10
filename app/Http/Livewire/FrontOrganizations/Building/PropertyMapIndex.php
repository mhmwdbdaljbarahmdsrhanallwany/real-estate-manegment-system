<?php

namespace App\Http\Livewire\FrontOrganizations\Building;

use Livewire\Component;

class PropertyMapIndex extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.Building.property-map-index')
        ->layout('front-organizations.layout.master');
    }
}
