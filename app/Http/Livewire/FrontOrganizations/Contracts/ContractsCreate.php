<?php

namespace App\Http\Livewire\FrontOrganizations\Contracts;

use Livewire\Component;

class ContractsCreate extends Component
{
    public function render()
    {
        return view('front-organizations.livewire.contracts.contracts-create')->layout('front-organizations.layout.master');
    }
}
