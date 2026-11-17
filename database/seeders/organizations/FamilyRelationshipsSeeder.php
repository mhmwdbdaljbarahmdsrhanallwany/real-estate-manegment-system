<?php

namespace Database\Seeders\organizations;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamilyRelationshipsSeeder extends Seeder
{
    
  public function run(): void
    {
        $relationships = [
            [
                'name' => 'رب الأسرة',
                'name_en' => 'Head of Family',
                'description' => 'المستأجر الرئيسي المسؤول عن الأسرة',
                'sort_order' => 1,
            ],
            [
                'name' => 'زوج',
                'name_en' => 'Husband',
                'description' => 'زوج رب الأسرة',
                'sort_order' => 2,
            ],
            [
                'name' => 'زوجة',
                'name_en' => 'Wife',
                'description' => 'زوجة رب الأسرة',
                'sort_order' => 3,
            ],
            [
                'name' => 'ابن',
                'name_en' => 'Son',
                'description' => 'ابن رب الأسرة',
                'sort_order' => 4,
            ],
            [
                'name' => 'ابنة',
                'name_en' => 'Daughter',
                'description' => 'ابنة رب الأسرة',
                'sort_order' => 5,
            ],
            [
                'name' => 'أب',
                'name_en' => 'Father',
                'description' => 'والد رب الأسرة',
                'sort_order' => 6,
            ],
            [
                'name' => 'أم',
                'name_en' => 'Mother',
                'description' => 'والدة رب الأسرة',
                'sort_order' => 7,
            ],
            [
                'name' => 'أخ',
                'name_en' => 'Brother',
                'description' => 'أخ رب الأسرة',
                'sort_order' => 8,
            ],
            [
                'name' => 'أخت',
                'name_en' => 'Sister',
                'description' => 'أخت رب الأسرة',
                'sort_order' => 9,
            ],
            [
                'name' => 'جد',
                'name_en' => 'Grandfather',
                'description' => 'جد رب الأسرة',
                'sort_order' => 10,
            ],
            [
                'name' => 'جدة',
                'name_en' => 'Grandmother',
                'description' => 'جدة رب الأسرة',
                'sort_order' => 11,
            ],
            [
                'name' => 'حفيد',
                'name_en' => 'Grandson',
                'description' => 'حفيد رب الأسرة',
                'sort_order' => 12,
            ],
            [
                'name' => 'حفيدة',
                'name_en' => 'Granddaughter',
                'description' => 'حفيدة رب الأسرة',
                'sort_order' => 13,
            ],
            [
                'name' => 'عم',
                'name_en' => 'Uncle',
                'description' => 'عم رب الأسرة',
                'sort_order' => 14,
            ],
            [
                'name' => 'عمة',
                'name_en' => 'Aunt',
                'description' => 'عمة رب الأسرة',
                'sort_order' => 15,
            ],
            [
                'name' => 'خال',
                'name_en' => 'Maternal Uncle',
                'description' => 'خال رب الأسرة',
                'sort_order' => 16,
            ],
            [
                'name' => 'خالة',
                'name_en' => 'Maternal Aunt',
                'description' => 'خالة رب الأسرة',
                'sort_order' => 17,
            ],
        ];

        foreach ($relationships as $relationship) {
            $exists = DB::table('family_relationships')
                ->where('name', $relationship['name'])
                ->exists();

            if (!$exists) {
                DB::table('family_relationships')->insert([
                    ...$relationship,
                    'created_at' => now(),
                ]);
            }
        }
    }


}
