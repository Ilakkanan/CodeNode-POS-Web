@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Product Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('products.edit', $product->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">
                    Delete
                </button>
            </form>
            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Product ID:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $product->product_id }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $product->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Barcode:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $product->barcode ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $product->category->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Brand:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $product->brand->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Vendor:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $product->vendor->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Unit Price:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ number_format($product->unit_price, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Created At:</label>
                <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $product->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Updated At:</label>
                <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $product->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection