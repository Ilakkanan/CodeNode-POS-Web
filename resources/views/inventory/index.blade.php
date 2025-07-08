@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Inventory Management</h1>
    </div>

    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <!-- Search Form -->
        <form id="searchForm" action="{{ route('inventory.index') }}" method="GET" class="w-full md:w-1/3">
            <input
                type="text"
                name="search"
                id="search"
                value="{{ request('search') }}"
                placeholder="Search inventory..."
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                autocomplete="off"
            >
            <input type="hidden" id="searchCaret" name="searchCaret" value="">
        </form>

        <!-- Status Filter -->
        <div class="w-full md:w-1/3">
            <form id="statusFilterForm" action="{{ route('inventory.index') }}" method="GET">
                <select 
                    name="status" 
                    id="status"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    onchange="this.form.submit()"
                >
                    <option value="">All Statuses</option>
                    <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                </select>
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
            </form>
        </div>
    </div>

    <script>
        // Search input handling
        const searchInput = document.getElementById('search');
        const searchForm = document.getElementById('searchForm');
        searchInput.addEventListener('input', function(e) {
            localStorage.setItem('inventorySearchCaret', searchInput.selectionStart);
            searchForm.submit();
        });
        
        window.addEventListener('DOMContentLoaded', function() {
            // Focus search input and restore caret position
            searchInput.focus();
            const caret = localStorage.getItem('inventorySearchCaret');
            if (caret) {
                searchInput.setSelectionRange(caret, caret);
                localStorage.removeItem('inventorySearchCaret');
            }
        });
    </script>

    <!-- Column Toggles -->
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0">
        <span class="font-semibold">Display Columns:</span>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle" data-col="product" checked>
                <span class="ml-2">Product</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle" data-col="quantity" checked>
                <span class="ml-2">Quantity</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle" data-col="alert" checked>
                <span class="ml-2">Alert Level</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle" data-col="status" checked>
                <span class="ml-2">Status</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle" data-col="actions" checked>
                <span class="ml-2">Actions</span>
            </label>
        </div>
    </div>

    <script>
        // Column visibility toggles
        document.addEventListener('DOMContentLoaded', function() {
            const defaultPreferences = {
                product: true,
                quantity: true,
                alert: true,
                status: true,
                actions: true
            };

            const savedPreferences = localStorage.getItem('inventoryColumnPreferences');
            const preferences = savedPreferences ? JSON.parse(savedPreferences) : defaultPreferences;

            document.querySelectorAll('.column-toggle').forEach(toggle => {
                const col = toggle.dataset.col;
                toggle.checked = preferences[col] || false;
                setColumnVisibility(col, toggle.checked);
            });
            
            document.querySelectorAll('.column-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const col = this.dataset.col;
                    setColumnVisibility(col, this.checked);
                    saveColumnPreferences();
                });
            });
        });

        function setColumnVisibility(col, show) {
            const colClass = 'col-' + col;
            document.querySelectorAll(`th.${colClass}, td.${colClass}`).forEach(cell => {
                cell.style.display = show ? '' : 'none';
            });
        }

        function saveColumnPreferences() {
            const preferences = {};
            document.querySelectorAll('.column-toggle').forEach(toggle => {
                preferences[toggle.dataset.col] = toggle.checked;
            });
            localStorage.setItem('inventoryColumnPreferences', JSON.stringify(preferences));
        }
    </script>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Inventory Table -->
    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-6 text-left col-product">Product</th>
                    <th class="py-3 px-6 text-left col-quantity">Available Quantity</th>
                    <th class="py-3 px-6 text-left col-alert">Alert Quantity</th>
                    <th class="py-3 px-6 text-left col-status">Status</th>
                    <th class="py-3 px-6 text-center col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inventory)
                <tr class="border-b border-gray-200 hover:bg-gray-100 @if($inventory->available_quantity < $inventory->alert_quantity) bg-red-50 @endif">
                    <td class="py-3 px-6 col-product">{{ $inventory->product->name }}</td>
                    <td class="py-3 px-6 col-quantity">{{ $inventory->available_quantity }}</td>
                    <td class="py-3 px-6 col-alert">{{ $inventory->alert_quantity }}</td>
                    <td class="py-3 px-6 col-status">
                        @if($inventory->available_quantity < $inventory->alert_quantity)
                            <span class="text-red-600 font-semibold">Low Stock</span>
                        @else
                            <span class="text-green-600 font-semibold">In Stock</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center col-actions">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('inventory.edit', $inventory->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $inventories->appends(request()->query())->links() }}
    </div>
</div>
@endsection