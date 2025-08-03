<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->where('quantity', '>', 0)
            ->get();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,transfer',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create sale
            $sale = Sale::create([
                'invoice_number' => Sale::generateInvoiceNumber(),
                'user_id' => $request->user()->id,
                'total_amount' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'final_amount' => 0,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;

            // Create sale items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $item['quantity'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                // Update product quantity
                $product->decrement('quantity', $item['quantity']);

                $totalAmount += $totalPrice;
            }

            // Update sale totals
            $sale->update([
                'total_amount' => $totalAmount,
                'final_amount' => $totalAmount,
            ]);

            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Sale completed successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::with(['user', 'saleItems.product.category'])->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sale = Sale::with('saleItems.product')->findOrFail($id);
        $products = Product::where('is_active', true)->get();
        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Implementation for editing sales if needed
        return redirect()->route('sales.index')
            ->with('info', 'Sale editing not implemented yet.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully.');
    }
}
