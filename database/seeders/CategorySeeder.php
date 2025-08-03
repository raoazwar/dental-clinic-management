<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Dental Supplies',
                'description' => 'Essential dental supplies and materials',
                'is_active' => true,
            ],
            [
                'name' => 'Medications',
                'description' => 'Pharmaceutical products and medications',
                'is_active' => true,
            ],
            [
                'name' => 'Equipment',
                'description' => 'Dental equipment and instruments',
                'is_active' => true,
            ],
            [
                'name' => 'Consumables',
                'description' => 'Disposable and consumable items',
                'is_active' => true,
            ],
        ];

        $categoryIds = [];
        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);
            $categoryIds[$categoryData['name']] = $category->id;
        }

        // Update existing products to use category IDs
        $productUpdates = [
            'Dental Floss' => 'Dental Supplies',
            'Toothpaste' => 'Dental Supplies',
            'Dental Mirror' => 'Equipment',
            'Local Anesthetic' => 'Medications',
            'Gauze Pads' => 'Consumables',
            'Dental Drill Bits' => 'Equipment',
        ];

        foreach ($productUpdates as $productName => $categoryName) {
            $product = Product::where('name', $productName)->first();
            if ($product && isset($categoryIds[$categoryName])) {
                $product->update(['category_id' => $categoryIds[$categoryName]]);
            }
        }

        $this->command->info('Categories created and products updated successfully!');
    }
}
