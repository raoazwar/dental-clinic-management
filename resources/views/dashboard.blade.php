<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if($isAdmin)
                    {{ __('Admin Dashboard') }}
                @else
                    {{ __('Staff Dashboard') }}
                @endif
            </h2>
            
            <!-- Filter Controls -->
            @if($isAdmin)
            <div class="flex items-center space-x-4">
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2" x-data="dashboardFilters">
                    <!-- Period Filter -->
                    <select name="period" class="rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" x-model="period">
                        <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>This Quarter</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                    
                    <!-- Custom Date Range -->
                    <div class="flex items-center space-x-2">
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               x-show="showCustom"
                               class="rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Start Date">
                        <span x-show="showCustom" class="text-gray-500">to</span>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                               x-show="showCustom"
                               class="rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="End Date">
                    </div>
                    
                    <x-modern-button variant="primary" size="sm" icon="search" type="submit">
                        Apply Filter
                    </x-modern-button>
                </form>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($isAdmin && $period)
            <!-- Filter Status (Admin Only) -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-800">
                        Currently showing data for: 
                        <span class="font-bold capitalize">{{ $period }}</span>
                        @if($period === 'custom' && $startDate && $endDate)
                            ({{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }})
                        @endif
                    </span>
                </div>
            </div>
            @endif
          
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @if($isAdmin)
                <!-- Total Sales (Admin Only) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Sales</dt>
                                    <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['total_sales'], 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Orders (Admin Only) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Orders</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_orders']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Order Value (Admin Only) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Avg Order Value</dt>
                                    <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['avg_order_value'], 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Low Stock Products (Both Staff and Admin) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Low Stock</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['low_stock_products']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Products -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_products']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                @if($isAdmin)
                <!-- Total Customers (Admin Only) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-pink-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Customers</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_customers']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Total Categories -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['total_categories']) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                @if($isAdmin)
                <!-- All Time Revenue (Admin Only) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-emerald-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">All Time Revenue</dt>
                                    <dd class="text-lg font-medium text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if($isAdmin)
            <!-- Charts and Analytics Section (Admin Only) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Sales Chart -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Sales Trend</h3>
                        <div class="h-64 flex items-end justify-between">
                            @forelse($chartData as $data)
                            <div class="flex flex-col items-center">
                                <div class="bg-blue-500 rounded-t w-8 mb-2 h-20"></div>
                                <span class="text-xs text-gray-500">{{ $data['label'] }}</span>
                                <span class="text-xs text-gray-700 font-medium">${{ number_format($data['sales'], 0) }}</span>
                            </div>
                            @empty
                            <div class="flex items-center justify-center h-full">
                                <p class="text-gray-500">No sales data available</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Distribution -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Payment Methods</h3>
                        <div class="space-y-3">
                            @forelse($paymentMethods as $method)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-3 
                                        {{ $method->payment_method === 'cash' ? 'bg-green-500' : 
                                           ($method->payment_method === 'card' ? 'bg-blue-500' : 'bg-purple-500') }}"></div>
                                    <span class="text-sm font-medium text-gray-900 capitalize">{{ $method->payment_method }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">${{ number_format($method->total_amount, 2) }}</p>
                                    <p class="text-xs text-gray-500">{{ $method->count }} orders</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No payment data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($isAdmin)
            <!-- Additional Analytics (Admin Only) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Top Selling Products -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Top Selling Products</h3>
                        <div class="space-y-3">
                            @forelse($topProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                    @if($product->sku)
                                    <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $product->total_quantity }} sold</p>
                                    <p class="text-xs text-gray-500">${{ number_format($product->total_revenue, 2) }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No product data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sales by Category -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Sales by Category</h3>
                        <div class="space-y-3">
                            @forelse($salesByCategory as $category)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $category->category_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $category->order_count }} orders</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">${{ number_format($category->total_sales, 2) }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No category data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Sales and Low Stock -->
            <div class="grid grid-cols-1 {{ $isAdmin ? 'lg:grid-cols-2' : 'lg:grid-cols-1' }} gap-8 mb-8">
                @if($isAdmin)
                <!-- Recent Sales (Admin Only) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Sales</h3>
                        <div class="space-y-3">
                            @forelse($recentSales as $sale)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $sale->invoice_number }}</p>
                                    <p class="text-xs text-gray-500">{{ $sale->user->name }} • {{ $sale->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">${{ number_format($sale->final_amount, 2) }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ $sale->payment_method }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No recent sales</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif

                <!-- Low Stock Products (Both Staff and Admin) -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Low Stock Products</h3>
                        <div class="space-y-3">
                            @forelse($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->category->name ?? 'No Category' }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->quantity < 5 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $product->quantity }} left
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">${{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center py-4">No low stock products</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Alpine.js for custom date range toggle
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardFilters', () => ({
                period: '{{ $period }}',
                showCustom: '{{ $period }}' === 'custom',
                init() {
                    this.$watch('period', value => {
                        this.showCustom = value === 'custom';
                        if (value !== 'custom') {
                            const startDateInput = document.querySelector('input[name="start_date"]');
                            const endDateInput = document.querySelector('input[name="end_date"]');
                            if (startDateInput) startDateInput.value = '';
                            if (endDateInput) endDateInput.value = '';
                        }
                    });
                }
            }));
        });
    </script>
</x-app-layout>
