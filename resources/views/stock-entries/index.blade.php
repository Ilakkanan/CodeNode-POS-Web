@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Stock Entries</h1>
        <a href="{{ route('stock-entries.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            New Stock Entry
        </a>
    </div>

    <form id="searchForm" action="{{ route('stock-entries.index') }}" method="GET" class="mb-4 w-full max-w-xs">
        <input
            type="text"
            name="search"
            id="search"
            value="{{ request('search') }}"
            placeholder="Search stock entries..."
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            autocomplete="off"
        >
        <input type="hidden" id="searchCaret" name="searchCaret" value="">
    </form>
    <script>
        const searchInput = document.getElementById('search');
        const searchForm = document.getElementById('searchForm');
        searchInput.addEventListener('input', function(e) {
            // Save caret position
            localStorage.setItem('stockEntrySearchCaret', searchInput.selectionStart);
            searchForm.submit();
        });
        window.addEventListener('DOMContentLoaded', function() {
            // Always focus search input on page load
            searchInput.focus();
            // Restore caret position if available
            const caret = localStorage.getItem('stockEntrySearchCaret');
            if (caret) {
                searchInput.setSelectionRange(caret, caret);
                localStorage.removeItem('stockEntrySearchCaret');
            }
        });
    </script>

    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0">
        <span class="font-semibold">Display Columns:</span>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="date">
                <span class="ml-2">Date</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="product">
                <span class="ml-2">Product</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="vendor">
                <span class="ml-2">Vendor</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="quantity">
                <span class="ml-2">Quantity</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="cost">
                <span class="ml-2">Unit Cost</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="cost">
                <span class="ml-2">Total Cost</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="actions">
                <span class="ml-2">Actions</span>
            </label>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-6 text-left col-date">Date</th>
                    <th class="py-3 px-6 text-left col-product">Product</th>
                    <th class="py-3 px-6 text-left col-vendor">Vendor</th>
                    <th class="py-3 px-6 text-left col-quantity">Quantity</th>
                    <th class="py-3 px-6 text-left col-cost">Unit Cost</th>
                    <th class="py-3 px-6 text-left col-cost">Total Cost</th>
                    <th class="py-3 px-6 text-center col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 col-date">{{ \Carbon\Carbon::parse($entry->entry_date)->format('M d, Y') }}</td>
                    <td class="py-3 px-6 col-product">{{ $entry->product->name }}</td>
                    <td class="py-3 px-6 col-vendor">{{ $entry->vendor->name }}</td>
                    <td class="py-3 px-6 col-quantity">{{ $entry->quantity }}</td>
                    <td class="py-3 px-6 col-cost">{{ number_format($entry->unit_cost, 2) }}</td>
                    <td class="py-3 px-6 col-cost">{{ number_format($entry->total_cost, 2) }}</td>
                    <td class="py-3 px-6 text-center col-actions">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('stock-entries.show', $entry->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('stock-entries.edit', $entry->id) }}" class="text-green-500 hover:text-green-700">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('stock-entries.destroy', $entry->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $entries->links() }}
    </div>

    <div class="mt-6">
        <a href="{{ route('stock-entries.trashed') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-trash mr-1"></i> View Trashed Stock Entries
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default preferences if none are saved
        const defaultPreferences = {
            date: true,
            product: true,
            vendor: true,
            quantity: true,
            cost: true,
            actions: true
        };

        // Load saved preferences from localStorage or use defaults
        const savedPreferences = localStorage.getItem('stockEntryColumnPreferences');
        const preferences = savedPreferences ? JSON.parse(savedPreferences) : defaultPreferences;

        // Apply preferences to checkboxes
        document.querySelectorAll('.column-toggle').forEach(toggle => {
            const col = toggle.dataset.col;
            toggle.checked = preferences[col] || false;
        });

        // Initialize column visibility based on checkboxes
        setColumnVisibility();
        
        // Add event listeners to all checkboxes
        document.querySelectorAll('.column-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                setColumnVisibility();
                saveColumnPreferences();
            });
        });
    });

    function setColumnVisibility() {
        document.querySelectorAll('.column-toggle').forEach(toggle => {
            const colClass = 'col-' + toggle.dataset.col;
            const show = toggle.checked;
            
            document.querySelectorAll('th.' + colClass).forEach(cell => {
                cell.style.display = show ? '' : 'none';
            });
            
            document.querySelectorAll('td.' + colClass).forEach(cell => {
                cell.style.display = show ? '' : 'none';
            });
        });
    }

    function saveColumnPreferences() {
        const preferences = {};
        document.querySelectorAll('.column-toggle').forEach(toggle => {
            preferences[toggle.dataset.col] = toggle.checked;
        });
        localStorage.setItem('stockEntryColumnPreferences', JSON.stringify(preferences));
    }
</script>
@endsection