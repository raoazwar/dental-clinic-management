<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product Details') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('products.edit', $product) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit Product
                </a>
                <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Product Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Product Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Product Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->category->name ?? 'No Category' }}</dd>
                                </div>
                                
                                @if($product->sku)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">SKU</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->sku }}</dd>
                                </div>
                                @endif
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        @if($product->is_active)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </dd>
                                </div>
                                
                                @if($product->description)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->description }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Stock and Pricing Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Stock & Pricing</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Quantity in Stock</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $product->quantity < 10 ? 'bg-red-100 text-red-800' : 
                                               ($product->quantity < 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $product->quantity }} units
                                        </span>
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($product->cost, 2) }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Selling Price</dt>
                                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($product->price, 2) }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($product->profit_margin, 1) }}%</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Value</dt>
                                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($product->total_value, 2) }}</dd>
                                </div>
                                
                                @if($product->expiry_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                                    <dd class="mt-1">
                                        <span class="text-sm {{ $product->isExpired() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                            {{ $product->expiry_date->format('M d, Y') }}
                                            @if($product->isExpired())
                                                (Expired)
                                            @endif
                                        </span>
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Stock Status Alerts -->
                    <div class="mt-8">
                        @if($product->quantity < 10)
                            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Low Stock Alert</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>This product is running low on stock. Consider reordering soon.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($product->isExpired())
                            <div class="bg-red-50 border border-red-200 rounded-md p-4 mt-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Expired Product</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>This product has expired and should not be sold.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Product History -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product History</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M d, Y \a\t h:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('M d, Y \a\t h:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Sales</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->saleItems->count() }} transactions</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 