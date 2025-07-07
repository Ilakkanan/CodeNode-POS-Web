@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Inventory Management</h1>
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
                    <th class="py-3 px-6 text-left">Product</th>
                    <th class="py-3 px-6 text-left">Available Quantity</th>
                    <th class="py-3 px-6 text-left">Alert Quantity</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inventory)
                <tr class="border-b border-gray-200 hover:bg-gray-100 @if($inventory->available_quantity < $inventory->alert_quantity) bg-red-50 @endif">
                    <td class="py-3 px-6">{{ $inventory->product->name }}</td>
                    <td class="py-3 px-6">{{ $inventory->available_quantity }}</td>
                    <td class="py-3 px-6">{{ $inventory->alert_quantity }}</td>
                    <td class="py-3 px-6">
                        @if($inventory->available_quantity < $inventory->alert_quantity)
                            <span class="text-red-600 font-semibold">Low Stock</span>
                        @else
                            <span class="text-green-600 font-semibold">In Stock</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
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
        {{ $inventories->links() }}
    </div>
</div>
@endsection