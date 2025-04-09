<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Design; // Import Design model
use App\Models\Category; // Import Category model
use Illuminate\Support\Facades\DB; // Import DB facade for transaction
use Illuminate\Support\Str; // Import Str facade

class DesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a default category exists or create one
        $defaultCategory = Category::firstOrCreate(
            ['name' => 'T-Shirts'],
            [
                'description' => 'Default category for t-shirt designs',
                'slug' => Str::slug('T-Shirts'), // Generate slug automatically
                'is_active' => true
            ]
        );

        $designs = [
            [
                'title' => 'Abstract Pattern',
                'description' => 'A vibrant t-shirt with a colorful abstract pattern.',
                'price' => 20.00,
                'image_path' => 'https://storage.googleapis.com/a1aa/image/QurtA2cSeO9dgnH68LxjlF-2JJFI8rfW-IeNdnFa6X8.jpg',
                'category_id' => $defaultCategory->id,
            ],
            [
                'title' => 'Mountain Landscape',
                'description' => 'A serene t-shirt featuring a minimalist mountain landscape.',
                'price' => 22.00,
                'image_path' => 'https://storage.googleapis.com/a1aa/image/asobplZwf25QXU-_Gh8UY2omLSWqv9uJKjh0U_9KJCI.jpg',
                'category_id' => $defaultCategory->id,
            ],
            [
                'title' => 'Vintage Car',
                'description' => 'A cool t-shirt with a classic vintage car illustration.',
                'price' => 25.00,
                'image_path' => 'https://storage.googleapis.com/a1aa/image/q0FMDcuxb_LkY2rrALB4yoqjYK-aPLrHwtgVenZkydI.jpg',
                'category_id' => $defaultCategory->id,
            ],
            [
                'title' => 'Floral Pattern',
                'description' => 'An elegant t-shirt adorned with a beautiful floral pattern.',
                'price' => 18.00,
                'image_path' => 'https://storage.googleapis.com/a1aa/image/ra9EnNmeTaOXou_6TG1VswBZrY_wv7NSAPfKhgS96HQ.jpg',
                'category_id' => $defaultCategory->id,
            ],
            [
                'title' => 'Geometric Shapes',
                'description' => 'A modern t-shirt featuring a pattern of geometric shapes.',
                'price' => 19.00,
                'image_path' => 'https://storage.googleapis.com/a1aa/image/cc_JGN7FUZYeuwmAxIXeQVk32A_r3w54WvBtxujfOPk.jpg',
                'category_id' => $defaultCategory->id,
            ],
            [
                'title' => 'Space Theme',
                'description' => 'An adventurous t-shirt with a captivating space theme illustration.',
                'price' => 23.00,
                'image_path' => 'https://storage.googleapis.com/a1aa/image/rcUwa3AcoSdAAel-bBJ-06XVTtQ4nFt0zNecmmVhbaQ.jpg',
                'category_id' => $defaultCategory->id,
            ],
             [
                'title' => 'Tropical Beach',
                'description' => 'Relax with this t-shirt featuring a tropical beach illustration.',
                'price' => 21.00,
                'image_path' => 'https://storage.googleapis.com/a1aa/image/RjZ-v13H736ACYp0iI95pDr7wCpRhA2aLtNYrlMvXE0.jpg',
                'category_id' => $defaultCategory->id,
            ],
            [
                'title' => 'City Skyline',
                'description' => 'Show your urban style with this city skyline t-shirt.',
                'price' => 24.00,
                'image_path' => 'https://storage.googleapis.com/a1aa/image/FS82Pmxc7tOBHjMYi15zeV5etGltEEuI-u2y1BxBBHM.jpg',
                'category_id' => $defaultCategory->id,
            ],
            // Add more designs if needed...
        ];

        // Use a transaction to ensure atomicity
        DB::transaction(function () use ($designs) {
            // Clear existing designs to avoid duplicates on re-seeding (optional)
            // Design::truncate(); // Be careful with truncate in production

            foreach ($designs as $designData) {
                Design::updateOrCreate(
                    ['title' => $designData['title']], // Find by title to avoid duplicates
                    $designData
                );
            }
        });

        $this->command->info('Design seeder finished.');
    }
}
