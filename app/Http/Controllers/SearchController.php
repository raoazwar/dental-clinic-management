<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Perform a global search across all entities
     */
    public function globalSearch(Request $request): JsonResponse
    {
        $query = $request->get('query', '');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter at least 2 characters to search',
                'results' => []
            ]);
        }

        $results = [
            'users' => $this->searchUsers($query),
            'products' => $this->searchProducts($query),
            'categories' => $this->searchCategories($query),
            'sales' => $this->searchSales($query),
        ];

        $totalResults = array_sum(array_map('count', $results));

        return response()->json([
            'success' => true,
            'query' => $query,
            'total_results' => $totalResults,
            'results' => $results
        ]);
    }

    /**
     * Search users
     */
    private function searchUsers(string $query): array
    {
        return User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('role', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'type' => 'user',
                    'title' => $user->name,
                    'subtitle' => $user->email,
                    'description' => ucfirst($user->role),
                    'url' => route('users.show', $user),
                    'icon' => 'user',
                    'status' => $user->email_verified_at ? 'Verified' : 'Unverified',
                    'created_at' => $user->created_at->format('M d, Y')
                ];
            })
            ->toArray();
    }

    /**
     * Search products
     */
    private function searchProducts(string $query): array
    {
        return Product::with('category')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'type' => 'product',
                    'title' => $product->name,
                    'subtitle' => $product->sku,
                    'description' => $this->getCategoryName($product),
                    'url' => route('products.show', $product),
                    'icon' => 'box',
                    'status' => $product->quantity > 0 ? 'In Stock' : 'Out of Stock',
                    'quantity' => $product->quantity,
                    'price' => '$' . number_format($product->price, 2)
                ];
            })
            ->toArray();
    }

    /**
     * Search categories
     */
    private function searchCategories(string $query): array
    {
        return Category::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'type' => 'category',
                    'title' => $category->name,
                    'subtitle' => $category->description,
                    'description' => $category->is_active ? 'Active' : 'Inactive',
                    'url' => route('categories.show', $category),
                    'icon' => 'folder',
                    'status' => $category->is_active ? 'Active' : 'Inactive',
                    'products_count' => $category->products()->count()
                ];
            })
            ->toArray();
    }

    /**
     * Search sales
     */
    private function searchSales(string $query): array
    {
        return Sale::with('saleItems.product')
            ->where('id', 'LIKE', "%{$query}%")
            ->orWhere('invoice_number', 'LIKE', "%{$query}%")
            ->orWhere('notes', 'LIKE', "%{$query}%")
            ->orWhereHas('saleItems.product', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'type' => 'sale',
                    'title' => 'Sale #' . $sale->invoice_number,
                    'subtitle' => 'Invoice: ' . $sale->invoice_number,
                    'description' => $sale->saleItems->count() . ' items',
                    'url' => route('sales.show', $sale),
                    'icon' => 'shopping-cart',
                    'status' => $sale->status ?: 'Completed',
                    'total' => '$' . number_format($sale->final_amount, 2),
                    'date' => $sale->created_at->format('M d, Y')
                ];
            })
            ->toArray();
    }

    /**
     * Safely get category name for a product
     */
    private function getCategoryName($product): string
    {
        try {
            if ($product->category && is_object($product->category) && isset($product->category->name)) {
                return $product->category->name;
            }
            return 'No Category';
        } catch (\Exception $e) {
            return 'No Category';
        }
    }
} 