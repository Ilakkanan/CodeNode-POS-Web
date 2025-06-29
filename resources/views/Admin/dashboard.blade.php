<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <x-app-layout>
        <div class="flex">
            <!-- Left Sidebar Menu -->
            <div class="w-64 min-h-screen bg-gray-800 text-white p-4">
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('categories.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                                <i class="fas fa-tags mr-2"></i> Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('brands.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                                <i class="fas fa-trademark mr-2"></i> Brands
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vendors.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
                                <i class="fas fa-truck mr-2"></i> Vendors
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">
                                <i class="fas fa-box mr-2"></i> Products
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">
                                <i class="fas fa-warehouse mr-2"></i> Stock Entry
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Logout at bottom -->
                    <ul class="space-y-2 mt-auto pt-8">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" class="block px-4 py-2 rounded hover:bg-gray-700" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                                </a>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Right Content Area -->
            <div class="flex-1 p-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    
    @stack('scripts')
</body>
</html>