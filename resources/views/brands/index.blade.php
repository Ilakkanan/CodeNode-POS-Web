@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Brands</h1>
        <a href="{{ route('brands.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            New Brand
        </a>
    </div>

    <form id="searchForm" action="{{ route('brands.index') }}" method="GET" class="mb-4 w-full max-w-xs">
        <input
            type="text"
            name="search"
            id="search"
            value="{{ request('search') }}"
            placeholder="Search brands..."
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
            localStorage.setItem('brandSearchCaret', searchInput.selectionStart);
            searchForm.submit();
        });
        window.addEventListener('DOMContentLoaded', function() {
            // Always focus search input on page load
            searchInput.focus();
            // Restore caret position if available
            const caret = localStorage.getItem('brandSearchCaret');
            if (caret) {
                searchInput.setSelectionRange(caret, caret);
                localStorage.removeItem('brandSearchCaret');
            }
        });
    </script>

    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:space-x-6 space-y-2 sm:space-y-0">
        <span class="font-semibold">Display Columns:</span>
        <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="name">
                <span class="ml-2">Name</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="column-toggle rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" data-col="slug">
                <span class="ml-2">Slug</span>
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
                name: true,
                slug: true,
                actions: true
            };

            // Load saved preferences from localStorage or use defaults
            const savedPreferences = localStorage.getItem('brandColumnPreferences');
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
            localStorage.setItem('brandColumnPreferences', JSON.stringify(preferences));
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
                    <th class="py-3 px-6 text-left col-name">Name</th>
                    <th class="py-3 px-6 text-left col-slug">Slug</th>
                    <th class="py-3 px-6 text-center col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 col-name">{{ $brand->name }}</td>
                    <td class="py-3 px-6 col-slug">{{ $brand->slug }}</td>
                    <td class="py-3 px-6 text-center col-actions">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('brands.show', $brand->id) }}" class="text-blue-500 hover:text-blue-700" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('brands.edit', $brand->id) }}" class="text-green-500 hover:text-green-700">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="inline">
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
        {{ $brands->links() }}
    </div>

    <div class="mt-6">
        <a href="{{ route('brands.trashed') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-trash mr-1"></i> View Trashed Brands
        </a>
    </div>
</div>
@endsection