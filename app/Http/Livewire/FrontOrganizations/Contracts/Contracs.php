<?php

namespace App\Http\Livewire\FrontOrganizations\Contracts;

use Livewire\Component;

class Contracs extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.contracts.contracs')
        ->layout('front-organizations.layout.master');
    }
}
