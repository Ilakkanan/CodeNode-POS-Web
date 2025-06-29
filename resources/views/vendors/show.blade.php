@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Vendor Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('vendors.edit', $vendor->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">
                    Delete
                </button>
            </form>
            <a href="{{ route('vendors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $vendor->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Contact No:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $vendor->contact_no }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">NIC:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $vendor->nic ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded min-h-[80px]">{{ $vendor->address }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Created At:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $vendor->created_at->format('M d, Y H:i') }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Updated At:</label>
                    <p class="text-gray-900 p-2 bg-gray-50 rounded">{{ $vendor->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection