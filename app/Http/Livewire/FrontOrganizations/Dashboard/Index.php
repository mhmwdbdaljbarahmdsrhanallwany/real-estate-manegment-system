<?php

namespace App\Http\Livewire\FrontOrganizations;

use Livewire\Component;

class Index extends Component
{
   
    public function render()
    {
        return view('front-organizations.index')
            ->layout('front-organizations.livewire.layout.master', [
                'title' => 'لوحة تحكم المؤسسة',
            ]);
    }
//   public function render()
//     {
//         return view('front-organizations.index')
//             ->layout('front-organizations.layout.master', [
//                 'title' => 'لوحة تحكم المؤسسة',
//             ]);
//     }
}
