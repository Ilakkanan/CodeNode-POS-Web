@extends('Admin.dashboard')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Create New Product</h1>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom CSS to match Select2 with other form elements */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.25rem !important;
            padding: 0.375rem 0.75rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5 !important;
            padding-left: 0 !important;
            color: #374151 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        .select2-container .select2-selection--single:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5) !important;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.25rem !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db !important;
            border-radius: 0.25rem !important;
            padding: 0.375rem 0.75rem !important;
        }
        .select2-search--dropdown .select2-search__field:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5) !important;
            border-color: #3b82f6 !important;
        }
    </style>

    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Product Name:</label>
                    <input type="text" name="name" id="name" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="barcode" class="block text-gray-700 text-sm font-bold mb-2">Barcode:</label>
                    <input type="text" name="barcode" id="barcode"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('barcode')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="unit_price" class="block text-gray-700 text-sm font-bold mb-2">Unit Price:</label>
                    <input type="number" step="0.01" name="unit_price" id="unit_price" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('unit_price')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
                    <select name="category_id" id="category_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="brand_id" class="block text-gray-700 text-sm font-bold mb-2">Brand:</label>
                    <select name="brand_id" id="brand_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="vendor_id" class="block text-gray-700 text-sm font-bold mb-2">Vendor:</label>
                    <select name="vendor_id" id="vendor_id" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Create Product
            </button>
            <a href="{{ route('products.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2 with focus on search field when opened
        $('select').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        }).on('select2:open', function() {
            // Focus on the search field when dropdown is opened
            document.querySelector('.select2-search__field').focus();
        });
        
        // Match the height and appearance with other form elements
        $('.select2-container').css('height', '42px');
    });
</script>
@endsection