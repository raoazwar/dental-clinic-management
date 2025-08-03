<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('New Sale') }}
            </h2>
            <a href="{{ route('sales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Sales
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                        @csrf
                        
                        <!-- Products Selection -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Select Products</h3>
                            <div id="productsContainer">
                                <div class="product-row grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Product</label>
                                        <select name="items[0][product_id]" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 product-select">
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->quantity }}">
                                                    {{ $product->name }} - ${{ number_format($product->price, 2) }} (Stock: {{ $product->quantity }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                        <input type="number" name="items[0][quantity]" min="1" value="1" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 quantity-input">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                        <input type="number" step="0.01" readonly
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 unit-price">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Total</label>
                                        <input type="number" step="0.01" readonly
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 item-total">
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="addProduct" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Add Another Product
                            </button>
                        </div>

                        <!-- Sale Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select name="payment_method" id="payment_method" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>

                        <!-- Sale Summary -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Sale Summary</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900" id="subtotal">$0.00</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tax</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900" id="tax">$0.00</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900" id="total">$0.00</dd>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('sales.index') }}" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Complete Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let productCount = 1;

            // Add product row
            document.getElementById('addProduct').addEventListener('click', function() {
                const container = document.getElementById('productsContainer');
                const newRow = document.createElement('div');
                newRow.className = 'product-row grid grid-cols-1 md:grid-cols-4 gap-4 mb-4';
                newRow.innerHTML = `
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product</label>
                        <select name="items[${productCount}][product_id]" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 product-select">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->quantity }}">
                                    {{ $product->name }} - ${{ number_format($product->price, 2) }} (Stock: {{ $product->quantity }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="items[${productCount}][quantity]" min="1" value="1" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 quantity-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                        <input type="number" step="0.01" readonly
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 unit-price">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total</label>
                        <input type="number" step="0.01" readonly
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 item-total">
                        <button type="button" class="mt-1 text-red-600 hover:text-red-800 remove-product">Remove</button>
                    </div>
                `;
                container.appendChild(newRow);
                productCount++;
                attachEventListeners();
            });

            // Attach event listeners
            function attachEventListeners() {
                // Product selection change
                document.querySelectorAll('.product-select').forEach(select => {
                    select.addEventListener('change', updatePrices);
                });

                // Quantity change
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('input', updatePrices);
                });

                // Remove product
                document.querySelectorAll('.remove-product').forEach(button => {
                    button.addEventListener('click', function() {
                        this.closest('.product-row').remove();
                        updateTotals();
                    });
                });
            }

            // Update prices and totals
            function updatePrices() {
                document.querySelectorAll('.product-row').forEach(row => {
                    const select = row.querySelector('.product-select');
                    const quantityInput = row.querySelector('.quantity-input');
                    const unitPriceInput = row.querySelector('.unit-price');
                    const itemTotalInput = row.querySelector('.item-total');

                    if (select.value) {
                        const option = select.options[select.selectedIndex];
                        const price = parseFloat(option.dataset.price);
                        const quantity = parseInt(quantityInput.value) || 0;
                        
                        unitPriceInput.value = price.toFixed(2);
                        itemTotalInput.value = (price * quantity).toFixed(2);
                    } else {
                        unitPriceInput.value = '';
                        itemTotalInput.value = '';
                    }
                });
                updateTotals();
            }

            // Update totals
            function updateTotals() {
                let subtotal = 0;
                document.querySelectorAll('.item-total').forEach(input => {
                    subtotal += parseFloat(input.value) || 0;
                });

                const tax = subtotal * 0.1; // 10% tax
                const total = subtotal + tax;

                document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
                document.getElementById('tax').textContent = '$' + tax.toFixed(2);
                document.getElementById('total').textContent = '$' + total.toFixed(2);
            }

            // Initial event listeners
            attachEventListeners();
        });
    </script>
</x-app-layout> 