@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Trashed Stock Entries</h1>
        <a href="{{ route('stock-entries.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Back to Stock Entries
        </a>
    </div>

    <form id="searchForm" action="{{ route('stock-entries.trashed') }}" method="GET" class="mb-4 w-full max-w-xs">
        <input
            type="text"
            name="search"
            id="search"
            value="{{ request('search') }}"
            placeholder="Search trashed entries..."
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            autocomplete="off"
        >
        <input type="hidden" id="searchCaret" name="searchCaret" value="">
    </form>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-max w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Reference #</th>
                    <th class="py-3 px-6 text-left">Product</th>
                    <th class="py-3 px-6 text-left">Vendor</th>
                    <th class="py-3 px-6 text-left">Quantity</th>
                    <th class="py-3 px-6 text-left">Total Cost</th>
                    <th class="py-3 px-6 text-left">Entry Date</th>
                    <th class="py-3 px-6 text-left">Deleted At</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($entries as $entry)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        {{ $entry->reference_number ?? 'N/A' }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $entry->product->name }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $entry->vendor->name }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $entry->quantity }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ number_format($entry->total_cost, 2) }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $entry->entry_date->format('M d, Y') }}
                    </td>
                    <td class="py-3 px-6 text-left">
                        {{ $entry->deleted_at->format('M d, Y H:i') }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <form action="{{ route('stock-entries.restore', $entry->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                    <i class="fas fa-trash-restore"></i>
                                </button>
                            </form>
                            <form action="{{ route('stock-entries.force-delete', $entry->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110" 
                                    onclick="return confirm('Are you sure you want to permanently delete this entry?')">
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
</div>

<script>
    const searchInput = document.getElementById('search');
    const searchForm = document.getElementById('searchForm');
    
    searchInput.addEventListener('input', function(e) {
        localStorage.setItem('trashedStockEntrySearchCaret', searchInput.selectionStart);
        searchForm.submit();
    });
    
    window.addEventListener('DOMContentLoaded', function() {
        searchInput.focus();
        const caret = localStorage.getItem('trashedStockEntrySearchCaret');
        if (caret) {
            searchInput.setSelectionRange(caret, caret);
            localStorage.removeItem('trashedStockEntrySearchCaret');
        }
    });
</script>
@endsection