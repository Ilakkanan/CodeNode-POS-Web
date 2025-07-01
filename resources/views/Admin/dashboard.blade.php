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
    <style>
        /* Mobile and tablet styles */
        @media (max-width: 1023px) {
            .sidebar-container {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                height: auto;
                z-index: 50;
                min-height: unset;
                padding: 0.5rem;
                background-color: #1f2937; /* bg-gray-800 */
            }
            
            .sidebar-nav {
                display: flex;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none; /* Firefox */
            }
            
            .sidebar-nav::-webkit-scrollbar {
                display: none; /* Chrome/Safari */
            }
            
            .sidebar-nav ul {
                display: flex;
                flex-shrink: 0;
                padding-bottom: 0.5rem;
                width: 100%;
            }
            
            .sidebar-nav li {
                flex: 0 0 auto;
                width: 25vw; /* Show 4 items initially */
                text-align: center;
                position: relative;
            }
            
            .sidebar-nav a {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 0.5rem;
                margin: 0 0.25rem;
                border-radius: 0.375rem;
                font-size: 0.75rem;
            }
            
            .sidebar-nav i {
                margin-right: 0;
                margin-bottom: 0.25rem;
                font-size: 1rem;
            }
            
            /* Active menu item dot indicator */
            .sidebar-nav a.active:after {
                content: '';
                position: absolute;
                bottom: -0.5rem;
                width: 6px;
                height: 6px;
                background-color: #ffffff;
                border-radius: 50%;
            }
            
            .logout-btn {
                position: fixed;
                bottom: 0.5rem;
                right: 0.5rem;
                z-index: 60;
                background-color: #1f2937;
                border-radius: 50%;
                width: 3rem;
                height: 3rem;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
            .logout-btn i {
                margin: 0;
            }
            
            .content-area {
                margin-bottom: 4rem; /* Space for bottom menu */
            }
        }
        
        /* Laptop/desktop styles */
        @media (min-width: 1024px) {
            .main-container {
                display: flex;
                min-height: 100vh;
            }
            
            .sidebar-container {
                width: 16rem;
                min-height: 100vh;
                position: relative;
                padding: 1rem;
                flex-shrink: 0;
            }
            
            .sidebar-nav {
                display: block;
                height: 100%;
            }
            
            .sidebar-nav ul {
                display: block;
            }
            
            .sidebar-nav li {
                width: auto;
                text-align: left;
            }
            
            .sidebar-nav a {
                display: flex;
                flex-direction: row;
                align-items: center;
                padding: 0.5rem 1rem;
                font-size: inherit;
            }
            
            .sidebar-nav i {
                margin-right: 0.5rem;
                margin-bottom: 0;
                font-size: inherit;
                width: 1.25rem;
                text-align: center;
            }
            
            .sidebar-nav a.active {
                background-color: #374151; /* bg-gray-700 */
            }
            
            .logout-btn {
                display: none;
            }
            
            .content-area {
                flex: 1;
                margin-bottom: 0;
                padding: 1.5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <x-app-layout>
        <div class="main-container">
            <!-- Left Sidebar Menu -->
            <div class="sidebar-container bg-gray-800 text-white">
                <nav class="sidebar-nav">
                    <ul class="space-y-2">
                        <li style="display: none;width: 0;">
                            
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}" class="hover:bg-gray-700 rounded {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                <span>Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('brands.index') }}" class="hover:bg-gray-700 rounded {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                                <i class="fas fa-trademark"></i>
                                <span>Brands</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vendors.index') }}" class="hover:bg-gray-700 rounded {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                                <i class="fas fa-truck"></i>
                                <span>Vendors</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:bg-gray-700 rounded">
                                <i class="fas fa-box"></i>
                                <span>Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:bg-gray-700 rounded">
                                <i class="fas fa-warehouse"></i>
                                <span>Stock Entry</span>
                            </a>
                        </li>
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
            <div class="content-area">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @yield('content')
                    </div>
                    <div class="bottom-box" style="height: 50px;"></div>
                </div>
            </div>
        </div>
    </x-app-layout>
    
    @stack('scripts')
   
</body>
</html>