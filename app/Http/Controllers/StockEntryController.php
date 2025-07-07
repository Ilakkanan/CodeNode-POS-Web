<?php

namespace App\Http\Controllers;

use App\Models\StockEntry;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Notifications\LowStockNotification;
use App\Models\User;

class StockEntryController extends Controller
{
    public function index()
    {
        $entries = StockEntry::with(['product', 'vendor', 'user'])
            ->latest()
            ->paginate(10);

        return view('stock-entries.index', compact('entries'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        return view('stock-entries.create', compact('products', 'vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'vendor_id' => 'required|exists:vendors,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'reference_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);

        $validated['total_cost'] = $validated['quantity'] * $validated['unit_cost'];
        $validated['user_id'] = auth()->id();

        // Create the stock entry
        StockEntry::create($validated);

        // // Update product stock
        // $product = Product::find($validated['product_id']);
        // $product->increment('stock_quantity', $validated['quantity']);

        // Update or create inventory record
        $inventory = Inventory::firstOrCreate(
            ['product_id' => $validated['product_id']],
            ['alert_quantity' => 20] // Default value
        );

        $inventory->increment('available_quantity', $validated['quantity']);

        // Notify all admins if low stock
        if ($inventory->available_quantity < $inventory->alert_quantity) {
            $admins = User::where('usertype', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new LowStockNotification($inventory));
            }
        }

        return redirect()->route('stock-entries.index')
            ->with('success', 'Stock entry created successfully.');
    }

    public function show(StockEntry $stockEntry)
    {
        // Eager load all necessary relationships
        $stockEntry->load(['product', 'vendor', 'user']);
        
        return view('stock-entries.show', compact('stockEntry'));
    }

    public function edit(StockEntry $stockEntry)
    {
        $products = Product::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        return view('stock-entries.edit', compact('stockEntry', 'products', 'vendors'));
    }

    public function update(Request $request, StockEntry $stockEntry)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'vendor_id' => 'required|exists:vendors,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'reference_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string'
        ]);

        // Calculate difference in quantity
        $quantityDiff = $validated['quantity'] - $stockEntry->quantity;
        $validated['total_cost'] = $validated['quantity'] * $validated['unit_cost'];

        $stockEntry->update($validated);

        // Update inventory if quantity changed
        if ($quantityDiff != 0) {
            $inventory = Inventory::where('product_id', $validated['product_id'])->first();
            if ($inventory) {
                $inventory->increment('available_quantity', $quantityDiff);
                // Notify all admins if low stock
                if ($inventory->available_quantity < $inventory->alert_quantity) {
                    $admins = User::where('usertype', 'admin')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new LowStockNotification($inventory));
                    }
                }
            }
        }

        return redirect()->route('stock-entries.index')
            ->with('success', 'Stock entry updated successfully.');
    }

    public function destroy(StockEntry $stockEntry)
    {
        // Decrement inventory before deleting
        $inventory = Inventory::where('product_id', $stockEntry->product_id)->first();
        
        if ($inventory) {
            $inventory->decrement('available_quantity', $stockEntry->quantity);
        }

        $stockEntry->delete();

        return redirect()->route('stock-entries.index')
            ->with('success', 'Stock entry moved to trash and inventory adjusted');
    }

    public function trashed(Request $request)
    {
        $query = StockEntry::onlyTrashed()
            ->with(['product', 'vendor', 'user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%$search%")
                  ->orWhere('notes', 'like', "%$search%")
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  })
                  ->orWhereHas('vendor', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }

        $entries = $query->latest()->paginate(10);

        return view('stock-entries.trashed', compact('entries'));
    }

    public function restore($id)
    {
        $entry = StockEntry::onlyTrashed()->findOrFail($id);
        
        // Increment inventory when restoring
        $inventory = Inventory::where('product_id', $entry->product_id)->first();
        
        if ($inventory) {
            $inventory->increment('available_quantity', $entry->quantity);
        }

        $entry->restore();

        return redirect()->route('stock-entries.trashed')
            ->with('success', 'Stock entry restored and inventory adjusted');
    }

    public function forceDelete($id)
    {
        $entry = StockEntry::onlyTrashed()->findOrFail($id);
        $entry->forceDelete();

        return redirect()->route('stock-entries.trashed')
            ->with('success', 'Stock entry permanently deleted');
    }
}