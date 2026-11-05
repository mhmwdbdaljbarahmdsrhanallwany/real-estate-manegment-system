<?php

namespace App\Http\Livewire\FrontOrganizations\Owner;

use App\Models\Owner;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Createowner extends Component
{
    use WithFileUploads;


    public function render()
{
    return view(
        'front-organizations.livewire.owner.createowner',
    )->layout('front-organizations.layout.master');
}
}