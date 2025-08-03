<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', 'month'); // today, week, month, quarter, year, custom
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Set date range based on period
        $dateRange = $this->getDateRange($period, $startDate, $endDate);
        
        // Get user role
        $isAdmin = Auth::user() && Auth::user()->role === 'administrator';
        
        // Get dashboard statistics with filters (role-based)
        $stats = $this->getDashboardStats($dateRange, $isAdmin);
        
        // Get recent sales (staff can see all sales, admin sees all)
        $recentSales = Sale::with('user')
            ->when($dateRange, function($query) use ($dateRange) {
                return $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get low stock products (both staff and admin need this)
        $lowStockProducts = Product::with('category')
            ->where('quantity', '<', 10)
            ->where('is_active', true)
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get();
        
        // Get sales data for chart based on period (both can see)
        $chartData = $this->getChartData($period, $dateRange);
        
        // Get top selling products (both can see)
        $topProducts = $this->getTopSellingProducts($dateRange);
        
        // Get sales by category (both can see)
        $salesByCategory = $this->getSalesByCategory($dateRange);
        
        // Get payment method distribution (both can see)
        $paymentMethods = $this->getPaymentMethodDistribution($dateRange);
        
        return view('dashboard', compact(
            'stats', 
            'recentSales', 
            'lowStockProducts', 
            'chartData',
            'topProducts',
            'salesByCategory',
            'paymentMethods',
            'period',
            'startDate',
            'endDate',
            'dateRange',
            'isAdmin'
        ));
    }
    
    private function getDateRange($period, $startDate = null, $endDate = null)
    {
        $now = Carbon::now(); // Use application timezone
        
        switch ($period) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay()
                ];
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'quarter':
                return [
                    'start' => $now->copy()->startOfQuarter(),
                    'end' => $now->copy()->endOfQuarter()
                ];
            case 'year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'custom':
                if ($startDate && $endDate) {
                    return [
                        'start' => Carbon::parse($startDate)->startOfDay(),
                        'end' => Carbon::parse($endDate)->endOfDay()
                    ];
                }
                break;
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
        
        return null;
    }
    
    private function getDashboardStats($dateRange = null, $isAdmin = false)
    {
        $productQuery = Product::where('is_active', true);
        $saleQuery = Sale::query();
        
        if ($dateRange) {
            $saleQuery->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }
        
        $totalSales = $saleQuery->sum('final_amount');
        $totalOrders = $saleQuery->count();
        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        if ($isAdmin) {
            // Admin sees all statistics
            return [
                'total_products' => $productQuery->count(),
                'low_stock_products' => Product::where('quantity', '<', 10)->where('is_active', true)->count(),
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'avg_order_value' => $avgOrderValue,
                'total_revenue' => Sale::sum('final_amount'), // All time
                'total_profit' => Sale::with('saleItems.product')->get()->sum('net_profit'),
                'total_customers' => User::count(),
                'total_categories' => Category::where('is_active', true)->count(),
            ];
        } else {
            // Staff sees only inventory and sales related stats
            return [
                'total_products' => $productQuery->count(),
                'low_stock_products' => Product::where('quantity', '<', 10)->where('is_active', true)->count(),
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'avg_order_value' => $avgOrderValue,
                'total_categories' => Category::where('is_active', true)->count(),
            ];
        }
    }
    
    private function getChartData($period, $dateRange = null)
    {
        if (!$dateRange) {
            $dateRange = $this->getDateRange('month');
        }
        
        switch ($period) {
            case 'today':
                return $this->getHourlySalesData($dateRange);
            case 'week':
                return $this->getDailySalesData($dateRange);
            case 'month':
                return $this->getDailySalesData($dateRange);
            case 'quarter':
            case 'year':
                return $this->getMonthlySalesData($dateRange);
            default:
                return $this->getDailySalesData($dateRange);
        }
    }
    
    private function getHourlySalesData($dateRange)
    {
        return Sale::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('SUM(final_amount) as total_sales')
        )
        ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
        ->groupBy('hour')
        ->orderBy('hour')
        ->get()
        ->map(function ($item) {
            return [
                'label' => date('ga', mktime($item->hour, 0, 0, 1, 1, 2000)),
                'sales' => $item->total_sales
            ];
        });
    }
    
    private function getDailySalesData($dateRange)
    {
        return Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(final_amount) as total_sales')
        )
        ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->map(function ($item) {
            return [
                'label' => Carbon::parse($item->date)->format('M d'),
                'sales' => $item->total_sales
            ];
        });
    }
    
    private function getMonthlySalesData($dateRange)
    {
        return Sale::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(final_amount) as total_sales')
        )
        ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            return [
                'label' => date('M', mktime(0, 0, 0, $item->month, 1)),
                'sales' => $item->total_sales
            ];
        });
    }
    
    private function getTopSellingProducts($dateRange = null)
    {
        $query = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_quantity', 'desc')
            ->limit(5);
            
        if ($dateRange) {
            $query->whereBetween('sales.created_at', [$dateRange['start'], $dateRange['end']]);
        }
        
        return $query->get();
    }
    
    private function getSalesByCategory($dateRange = null)
    {
        $query = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(sale_items.quantity * sale_items.unit_price) as total_sales'),
                DB::raw('COUNT(DISTINCT sales.id) as order_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc');
            
        if ($dateRange) {
            $query->whereBetween('sales.created_at', [$dateRange['start'], $dateRange['end']]);
        }
        
        return $query->get();
    }
    
    private function getPaymentMethodDistribution($dateRange = null)
    {
        $query = Sale::select(
            'payment_method',
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(final_amount) as total_amount')
        )
        ->groupBy('payment_method')
        ->orderBy('total_amount', 'desc');
        
        if ($dateRange) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }
        
        return $query->get();
    }
}
