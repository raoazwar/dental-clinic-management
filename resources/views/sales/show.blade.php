<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sale Details') }} - {{ $sale->invoice_number }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('sales.edit', $sale) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Edit Sale
                </a>
                <a href="{{ route('sales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Sales
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Sale Information -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Sale Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $sale->invoice_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $sale->created_at->format('M d, Y \a\t h:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $sale->payment_method === 'cash' ? 'bg-green-100 text-green-800' : 
                                               ($sale->payment_method === 'card' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </dd>
                                </div>
                                @if($sale->notes)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $sale->notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sold By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $sale->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $sale->user->email }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sale Items -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sale Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sale->saleItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->product->category->name ?? 'No Category' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sale Summary -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Sale Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <dl class="space-y-4">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($sale->total_amount, 2) }}</dd>
                                </div>
                                @if($sale->tax_amount > 0)
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Tax</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($sale->tax_amount, 2) }}</dd>
                                </div>
                                @endif
                                @if($sale->discount_amount > 0)
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Discount</dt>
                                    <dd class="text-sm text-gray-900">-${{ number_format($sale->discount_amount, 2) }}</dd>
                                </div>
                                @endif
                                <div class="flex justify-between border-t pt-4">
                                    <dt class="text-lg font-medium text-gray-900">Total</dt>
                                    <dd class="text-lg font-medium text-gray-900">${{ number_format($sale->final_amount, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Net Profit</dt>
                                    <dd class="text-lg font-medium text-green-600">${{ number_format($sale->net_profit, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($sale->final_amount > 0)
                                            {{ number_format(($sale->net_profit / $sale->final_amount) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 