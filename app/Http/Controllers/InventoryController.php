<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Notifications\LowStockNotification;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // Main query for displaying inventory
        $query = Inventory::with(['product' => function($query) {
                $query->withTrashed();
            }])
            ->whereHas('product', function($query) {
                $query->whereNull('deleted_at');
            });

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('available_quantity', 'like', "%$search%")
                  ->orWhere('alert_quantity', 'like', "%$search%")
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status == 'low') {
                $query->whereColumn('available_quantity', '<', 'alert_quantity');
            } elseif ($request->status == 'in_stock') {
                $query->whereColumn('available_quantity', '>=', 'alert_quantity');
            }
        }

        // Order and paginate the results
        $inventories = $query->orderByRaw('available_quantity < alert_quantity DESC')
                            ->paginate(10)
                            ->appends($request->query());

        // Separate query for low stock notifications (shouldn't affect main query)
        $this->checkLowStockNotifications($request);

        return view('inventory.index', compact('inventories'));
    }

    protected function checkLowStockNotifications(Request $request)
    {
        // Only check for low stock if we're not filtering for specific status
        if (!$request->has('status')) {
            $lowStockItems = Inventory::whereColumn('available_quantity', '<', 'alert_quantity')
                ->whereHas('product', function($query) {
                    $query->whereNull('deleted_at');
                })
                ->get();
                
            foreach ($lowStockItems as $item) {
                // Check if user already has an unread notification for this product today
                $hasExistingNotification = auth()->user()->unreadNotifications()
                    ->where('data->product_id', $item->product_id)
                    ->whereDate('created_at', now()->toDateString())
                    ->exists();
                    
                // Check if user dismissed a notification for this product today
                $hasDismissedToday = auth()->user()->readNotifications()
                    ->where('data->product_id', $item->product_id)
                    ->whereDate('read_at', now()->toDateString())
                    ->exists();
                    
                if (!$hasExistingNotification && !$hasDismissedToday) {
                    auth()->user()->notify(new LowStockNotification($item));
                }
            }
        }
    }

    public function edit(Inventory $inventory)
    {
        if ($inventory->product && $inventory->product->trashed()) {
            abort(404, 'The associated product has been deleted');
        }
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        if ($inventory->product && $inventory->product->trashed()) {
            abort(404, 'The associated product has been deleted');
        }

        $validated = $request->validate([
            'alert_quantity' => 'required|integer|min:1'
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Alert quantity updated successfully.');
    }
}