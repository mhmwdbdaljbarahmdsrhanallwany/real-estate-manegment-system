<?php

namespace App\Http\Livewire\FrontOrganizations\Owner;


use App\Models\FrontOrganizations\Owner\OwnerModels;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class Owner extends Component
{


public function render()
{
    return view('front-organizations.livewire.owner.owner')
        ->layout('front-organizations.layout.master');
}


}
