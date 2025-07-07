<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Notifications\LowStockNotification;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('product')
            ->orderByRaw('available_quantity < alert_quantity DESC')
            ->paginate(10);

        // Check for low stock items and notify
        $lowStockItems = Inventory::whereColumn('available_quantity', '<', 'alert_quantity')->get();
        foreach ($lowStockItems as $item) {
            auth()->user()->notify(new LowStockNotification($item));
        }

        return view('inventory.index', compact('inventories'));
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'alert_quantity' => 'required|integer|min:1'
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')
            ->with('success', 'Alert quantity updated successfully.');
    }
}