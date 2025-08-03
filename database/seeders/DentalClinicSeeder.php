<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DentalClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Administrator User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@dentalclinic.com',
            'password' => Hash::make('password'),
            'role' => 'administrator',
        ]);

        // Create Staff User
        $staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@dentalclinic.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

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

        // Create Sample Products
        $products = [
            [
                'name' => 'Dental Floss',
                'category_id' => $categoryIds['Dental Supplies'],
                'quantity' => 150,
                'cost' => 2.50,
                'price' => 5.99,
                'expiry_date' => now()->addMonths(12),
                'description' => 'Waxed dental floss for daily oral hygiene',
                'sku' => 'DF001',
                'is_active' => true,
            ],
            [
                'name' => 'Toothpaste',
                'category_id' => $categoryIds['Dental Supplies'],
                'quantity' => 75,
                'cost' => 3.00,
                'price' => 7.50,
                'expiry_date' => now()->addMonths(18),
                'description' => 'Fluoride toothpaste for cavity protection',
                'sku' => 'TP002',
                'is_active' => true,
            ],
            [
                'name' => 'Dental Mirror',
                'category_id' => $categoryIds['Equipment'],
                'quantity' => 25,
                'cost' => 15.00,
                'price' => 35.00,
                'expiry_date' => null,
                'description' => 'Stainless steel dental mirror',
                'sku' => 'DM003',
                'is_active' => true,
            ],
            [
                'name' => 'Local Anesthetic',
                'category_id' => $categoryIds['Medications'],
                'quantity' => 8,
                'cost' => 25.00,
                'price' => 60.00,
                'expiry_date' => now()->addMonths(6),
                'description' => 'Lidocaine 2% for local anesthesia',
                'sku' => 'LA004',
                'is_active' => true,
            ],
            [
                'name' => 'Gauze Pads',
                'category_id' => $categoryIds['Consumables'],
                'quantity' => 200,
                'cost' => 0.50,
                'price' => 1.25,
                'expiry_date' => now()->addMonths(24),
                'description' => 'Sterile gauze pads 2x2 inches',
                'sku' => 'GP005',
                'is_active' => true,
            ],
            [
                'name' => 'Dental Drill Bits',
                'category_id' => $categoryIds['Equipment'],
                'quantity' => 12,
                'cost' => 45.00,
                'price' => 95.00,
                'expiry_date' => null,
                'description' => 'High-speed dental drill bits set',
                'sku' => 'DB006',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Create Sample Sales
        $sales = [
            [
                'invoice_number' => 'INV-202501-0001',
                'user_id' => $admin->id,
                'total_amount' => 15.98,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'final_amount' => 15.98,
                'payment_method' => 'cash',
                'status' => 'completed',
                'notes' => 'Regular dental supplies purchase',
            ],
            [
                'invoice_number' => 'INV-202501-0002',
                'user_id' => $staff->id,
                'total_amount' => 130.00,
                'tax_amount' => 0,
                'discount_amount' => 10.00,
                'final_amount' => 120.00,
                'payment_method' => 'card',
                'status' => 'completed',
                'notes' => 'Equipment purchase with discount',
            ],
        ];

        foreach ($sales as $saleData) {
            $sale = Sale::create($saleData);
            
            // Create sample sale items
            if ($sale->invoice_number === 'INV-202501-0001') {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => 1, // Dental Floss
                    'quantity' => 2,
                    'unit_price' => 5.99,
                    'total_price' => 11.98,
                ]);
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => 2, // Toothpaste
                    'quantity' => 1,
                    'unit_price' => 7.50,
                    'total_price' => 7.50,
                ]);
            } else {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => 3, // Dental Mirror
                    'quantity' => 2,
                    'unit_price' => 35.00,
                    'total_price' => 70.00,
                ]);
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => 6, // Dental Drill Bits
                    'quantity' => 1,
                    'unit_price' => 95.00,
                    'total_price' => 95.00,
                ]);
            }
        }

        $this->command->info('Dental Clinic sample data created successfully!');
        $this->command->info('Admin User: admin@dentalclinic.com / password');
        $this->command->info('Staff User: staff@dentalclinic.com / password');
    }
}
