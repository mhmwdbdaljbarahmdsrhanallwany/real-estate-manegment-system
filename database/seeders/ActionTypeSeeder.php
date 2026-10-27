<?php

namespace Database\Seeders;

use App\Models\Admin\ActionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActionType::insert([

            [
                'name'=>'إنشاء اشتراك',
                'code'=>'subscription_created',
                'is_active'=>true,
                'is_system'=>true,
                'sort_order'=>1,
            ],
        
            [
                'name'=>'تعديل اشتراك',
                'code'=>'subscription_updated',
                'is_active'=>true,
                'is_system'=>true,
                'sort_order'=>2,
            ],
        
            [
                'name'=>'إنشاء فاتورة',
                'code'=>'invoice_created',
                'is_active'=>true,
                'is_system'=>true,
                'sort_order'=>3,
            ],
        
        ]);
    }
}
