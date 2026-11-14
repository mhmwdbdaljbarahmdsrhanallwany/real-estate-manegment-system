<?php

namespace App\Http\Livewire\FrontOrganizations\Building\Unets;

use Livewire\Component;

class UnitsIndex extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.Building.unets.units-index')
        ->layout('front-organizations.layout.master');
    }
}
