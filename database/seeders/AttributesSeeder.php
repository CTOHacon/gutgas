<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AttributeGroup::truncate();
        Attribute::truncate();

        $testAttributeGroups = [
            [
                'name' => 'Тип',
                'use_in_filters' => true,
                'attributes' => [
                    'Композитні',
                    'Суперглегкі',
                    'Комбіновані',
                    'Промислові',
                ],
            ],
            [
                'name' => 'Об`єм (л)',
                'use_in_filters' => true,
                'attributes' => [
                    '100',
                    '150',
                    '200',
                    '250',
                    '300',
                ],
            ],
            [
                'name' => 'Висота (см)',
                'use_in_filters' => true,
                'attributes' => [
                    '40',
                    '50',
                    '60',
                    '70',
                ],
            ],
            [
                'name' => 'Виробництво',
                'use_in_filters' => false,
                'attributes' => [
                    'Європейський союз',
                    'Китай',
                ],
            ]
        ];

        foreach ($testAttributeGroups as $testAttributeGroup) {
            $attributeGroup = AttributeGroup::create([
                'name' => $testAttributeGroup['name'],
                'use_in_filters' => $testAttributeGroup['use_in_filters'],
            ]);

            foreach ($testAttributeGroup['attributes'] as $attribute) {
                Attribute::create([
                    'attribute_group_id' => $attributeGroup->id,
                    'name' => $attribute,
                ]);
            }
        }
    }
}
