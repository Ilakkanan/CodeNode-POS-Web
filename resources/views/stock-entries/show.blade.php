@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Stock Entry Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('stock-entries.edit', $stockEntry->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <form action="{{ route('stock-entries.destroy', $stockEntry->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">
                    Delete
                </button>
            </form>
            <a href="{{ route('stock-entries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Reference Number:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $stockEntry->reference_number ?? 'N/A' }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $stockEntry->product->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Vendor:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $stockEntry->vendor->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $stockEntry->quantity }}</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Unit Cost:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ number_format($stockEntry->unit_cost, 2) }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Total Cost:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ number_format($stockEntry->total_cost, 2) }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Entry Date:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $stockEntry->entry_date->format('M d, Y') }}</p>
                </div>

            </div>
        </div>

        <div class="mt-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Notes:</label>
            <p class="text-gray-900 p-4 bg-gray-50 rounded">{{ $stockEntry->notes ?? 'No notes available' }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Created At:</label>
                <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $stockEntry->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Updated At:</label>
                <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $stockEntry->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection