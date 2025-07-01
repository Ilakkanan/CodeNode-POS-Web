<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Code Node (PVT) LTD') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#2563eb">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Code Node">
        <link rel="apple-touch-icon" href="{{ asset('Images/logo.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Custom CSS -->
        <link href="{{ asset('css/auth.css') }}" rel="stylesheet">

        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(registration => {
                            console.log('ServiceWorker registration successful');
                        })
                        .catch(err => {
                            console.log('ServiceWorker registration failed: ', err);
                        });
                });
            }
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="animated-bg"></div>
        <div class="min-h-screen flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-sm space-y-8">
                <div class="text-center">
                    <h1 class="welcome-text text-2xl font-bold tracking-tight sm:text-3xl">Welcome to Code Node (PVT) LTD</h1>
                    <p class="time-text mt-2 text-sm text-gray-600" id="current-time"></p>
                </div>

                <div class="mt-8 bg-white py-6 px-4 shadow-xl rounded-lg sm:px-8 login-container">
                    {{ $slot }}
                </div>

                <div class="text-center mt-8">
                    <p class="text-sm text-gray-600 mb-2">From</p>
                    <a href="https://codenode.lk/" class="inline-block">
                        <img src="{{ asset('Images/logo.png') }}" alt="Code Node Logo" class="company-logo mx-auto h-8 w-auto sm:h-10">
                    </a>
                </div>
            </div>
        </div>

        <script>
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString();
                const dateString = now.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                document.getElementById('current-time').textContent = `${dateString} | ${timeString}`;
            }
            
            updateTime();
            setInterval(updateTime, 1000);
        </script>
    </body>
</html>