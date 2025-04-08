<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Action',
                'slug' => 'action',
                'description' => 'Action-themed merchandise and designs',
                'is_active' => true
            ],
            [
                'name' => 'Drama',
                'slug' => 'drama',
                'description' => 'Drama-themed merchandise and designs',
                'is_active' => true
            ],
            [
                'name' => 'Horror',
                'slug' => 'horror',
                'description' => 'Horror-themed merchandise and designs',
                'is_active' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            // Use updateOrCreate to avoid duplicate entries based on the slug
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']], // Find by slug
                $categoryData // Data to create or update with
            );
        }
    }
}
