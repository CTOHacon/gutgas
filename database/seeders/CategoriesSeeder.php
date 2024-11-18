<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Category::truncate();

        for ($i = 0; $i < 5; $i++) {
            $topLevelCategory = Category::create([
                'name' => 'Top Level Category ' . $i,
                'parent_id' => null,
            ]);

            for ($j = 0; $j < 3; $j++) {
                $secondLevelCategory = Category::create([
                    'name' => 'Second Level Category ' . $i . '.' . $j,
                    'parent_id' => $topLevelCategory->id,
                ]);

                for ($k = 0; $k < 2; $k++) {
                    $thirdLevelCategory = Category::create([
                        'name' => 'Third Level Category ' . $i . '.' . $j . '.' . $k,
                        'parent_id' => $secondLevelCategory->id,
                    ]);
                }
            }
        }
    }
}
