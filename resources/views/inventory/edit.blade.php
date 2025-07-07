@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Edit Alert Quantity</h1>

    <form action="{{ route('inventory.update', $inventory->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="product" class="block text-gray-700 font-bold mb-2">Product:</label>
            <input type="text" id="product" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $inventory->product->name }}" readonly>
        </div>

        <div class="mb-4">
            <label for="available_quantity" class="block text-gray-700 font-bold mb-2">Available Quantity:</label>
            <input type="text" id="available_quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $inventory->available_quantity }}" readonly>
        </div>

        <div class="mb-4">
            <label for="alert_quantity" class="block text-gray-700 font-bold mb-2">Alert Quantity:</label>
            <input type="number" name="alert_quantity" id="alert_quantity" value="{{ $inventory->alert_quantity }}" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update
            </button>
            <a href="{{ route('inventory.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection