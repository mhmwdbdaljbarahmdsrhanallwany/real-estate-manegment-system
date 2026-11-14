<?php

namespace App\Http\Livewire\FrontOrganizations\Building\Unets;

use Livewire\Component;

class UnitsAvailable extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.Building.unets.units-available')
        ->layout('front-organizations.layout.master');
    }
}
