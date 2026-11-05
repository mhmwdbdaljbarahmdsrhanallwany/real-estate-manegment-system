<?php

namespace App\Http\Livewire\FrontOrganizations\Building;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Building extends Component
{
    use WithFileUploads;


    public function render()
{
    return view(
        'front-organizations.livewire.Building.building-index',
    )->layout('front-organizations.layout.master');
}
}