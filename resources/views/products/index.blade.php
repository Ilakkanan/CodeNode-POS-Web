@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Products</h1>
        <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create New Product
        </a>
    </div>

    <form id="searchForm" action="{{ route('products.index') }}" method="GET" class="mb-4 w-full max-w-xs">
        <input
            type="text"
            name="search"
            id="search"
            value="{{ request('search') }}"
            placeholder="Search products..."
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
            localStorage.setItem('productSearchCaret', searchInput.selectionStart);
            searchForm.submit();
        });
        window.addEventListener('DOMContentLoaded', function() {
            // Always focus search input on page load
            searchInput.focus();
            // Restore caret position if available
            const caret = localStorage.getItem('productSearchCaret');
            if (caret) {
                searchInput.setSelectionRange(caret, caret);
                localStorage.removeItem('productSearchCaret');
            }
        });
    </script>

    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0">
        <span class="font-semibold">Display Columns:</span>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="id">
                <span class="ml-2">ID</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="name">
                <span class="ml-2">Name</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="barcode">
                <span class="ml-2">Barcode</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="category">
                <span class="ml-2">Category</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="brand">
                <span class="ml-2">Brand</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="price">
                <span class="ml-2">Unit Price</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="actions">
                <span class="ml-2">Actions</span>
            </label>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default preferences if none are saved
            const defaultPreferences = {
                id: true,
                name: true,
                barcode: true,
                category: true,
                brand: true,
                price: true,
                actions: true
            };

            // Load saved preferences from localStorage or use defaults
            const savedPreferences = localStorage.getItem('productColumnPreferences');
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
                
                // Toggle header cells
                document.querySelectorAll('th.' + colClass).forEach(cell => {
                    cell.style.display = show ? '' : 'none';
                });
                
                // Toggle body cells
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
            localStorage.setItem('productColumnPreferences', JSON.stringify(preferences));
        }
    </script>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-6 text-left col-id">ID</th>
                    <th class="py-3 px-6 text-left col-name">Name</th>
                    <th class="py-3 px-6 text-left col-barcode">Barcode</th>
                    <th class="py-3 px-6 text-left col-category">Category</th>
                    <th class="py-3 px-6 text-left col-brand">Brand</th>
                    <th class="py-3 px-6 text-left col-price">Unit Price</th>
                    <th class="py-3 px-6 text-center col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 col-id">{{ $product->product_id }}</td>
                    <td class="py-3 px-6 col-name">{{ $product->name }}</td>
                    <td class="py-3 px-6 col-barcode">{{ $product->barcode ?? 'N/A' }}</td>
                    <td class="py-3 px-6 col-category">{{ $product->category->category }}</td>
                    <td class="py-3 px-6 col-brand">{{ $product->brand->name }}</td>
                    <td class="py-3 px-6 col-price">{{ number_format($product->unit_price, 2) }}</td>
                    <td class="py-3 px-6 text-center col-actions">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('products.show', $product->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" class="text-green-500 hover:text-green-700">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
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
        {{ $products->links() }}
    </div>

    <div class="mt-6">
        <a href="{{ route('products.trashed') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-trash mr-1"></i> View Trashed Products
        </a>
    </div>
</div>
@endsection