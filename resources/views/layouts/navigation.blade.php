<nav x-data="{ open: false, notificationsOpen: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Desktop Notification & Settings -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notification Bell -->
                <div class="relative mr-4" x-data="{ open: false }">
                    <button @click="open = !open" class="relative focus:outline-none">
                        <i class="fas fa-bell text-gray-500 text-xl"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
                        @endif
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded shadow-lg z-50" style="display: none;" x-cloak>
                        <div class="p-4 border-b font-bold">Notifications</div>
                        <ul class="max-h-64 overflow-y-auto">
                            @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                <li class="px-4 py-2 border-b hover:bg-gray-100">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            {{ $notification->data['message'] ?? 'Notification' }}
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 ml-2">
                                                Dismiss
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="px-4 py-2 text-gray-500">No new notifications.</li>
                            @endforelse
                        </ul>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <div class="p-2 text-center border-t">
                                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:underline text-sm">Mark all as read</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile menu button and notification bell -->
            <div class="-me-2 flex items-center sm:hidden space-x-2">
                <!-- Notification Bell -->
                <button @click="notificationsOpen = !notificationsOpen" class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 relative">
                    <i class="fas fa-bell text-xl"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-1.5 right-1.5 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
                    @endif
                </button>
                
                <!-- Hamburger Menu -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Notifications Panel (shown as overlay) -->
    <div x-show="notificationsOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="fixed inset-0 z-50 bg-white sm:hidden" 
         style="display: none;"
         @click.away="notificationsOpen = false">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
            <button @click="notificationsOpen = false" class="text-gray-500 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-[calc(100%-57px)] overflow-y-auto">
            <ul class="divide-y divide-gray-200">
                @forelse(Auth::user()->unreadNotifications->take(10) as $notification)
                    <li class="px-4 py-3 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                @csrf
                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 ml-2">
                                    Dismiss
                                </button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 text-center text-gray-500">No new notifications</li>
                @endforelse
            </ul>
            @if(Auth::user()->unreadNotifications->count() > 0)
                <div class="sticky bottom-0 bg-white border-t border-gray-200 px-4 py-2">
                    <form method="POST" action="{{ route('notifications.markAllRead') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Mark all as read
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-1 pb-1 border-t border-gray-200">
            <div class="px-4 py-3">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-1 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>