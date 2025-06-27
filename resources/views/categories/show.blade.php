@extends('Admin.dashboard')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Category Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('categories.edit', $category->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
                <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded p-6">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">category:</label>
                <p class="text-gray-900">{{ $category->category }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <p class="text-gray-900">{{ $category->description ?? 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Created At:</label>
                <p class="text-gray-900">{{ $category->created_at->format('M d, Y H:i') }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Updated At:</label>
                <p class="text-gray-900">{{ $category->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection