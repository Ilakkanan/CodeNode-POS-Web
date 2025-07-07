@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Stock Entry</h1>

    <form action="{{ route('stock-entries.update', $stockEntry->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label for="product_id" class="block text-gray-700 text-sm font-bold mb-2">Product:</label>
                    <select name="product_id" id="product_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ $stockEntry->product_id == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="vendor_id" class="block text-gray-700 text-sm font-bold mb-2">Vendor:</label>
                    <select name="vendor_id" id="vendor_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ $stockEntry->vendor_id == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" 
                           value="{{ old('quantity', $stockEntry->quantity) }}" required min="1"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('quantity')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label for="unit_cost" class="block text-gray-700 text-sm font-bold mb-2">Unit Cost:</label>
                    <input type="number" step="0.01" name="unit_cost" id="unit_cost" 
                           value="{{ old('unit_cost', $stockEntry->unit_cost) }}" required min="0"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('unit_cost')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="entry_date" class="block text-gray-700 text-sm font-bold mb-2">Entry Date:</label>
                    <input type="date" name="entry_date" id="entry_date" 
                           value="{{ old('entry_date', $stockEntry->entry_date->format('Y-m-d')) }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('entry_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="reference_number" class="block text-gray-700 text-sm font-bold mb-2">Reference Number:</label>
                    <input type="text" name="reference_number" id="reference_number" 
                           value="{{ old('reference_number', $stockEntry->reference_number) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('reference_number')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes:</label>
            <textarea name="notes" id="notes" rows="3"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes', $stockEntry->notes) }}</textarea>
            @error('notes')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Stock Entry
            </button>
            <a href="{{ route('stock-entries.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection