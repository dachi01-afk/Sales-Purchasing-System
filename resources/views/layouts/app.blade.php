<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('dark') === 'true' }" x-init="dark ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')" x-effect="dark ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark'); localStorage.setItem('dark', dark)">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900">

    {{-- NAVBAR --}}
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-2.5 fixed left-0 right-0 top-0 z-50">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex justify-start items-center">
                <button data-drawer-target="drawer-navigation" data-drawer-toggle="drawer-navigation" aria-controls="drawer-navigation" class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100">
                    <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Toggle sidebar</span>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center mr-4">
                    <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">{{config('app.name')}}</span>
                </a>
            </div>
            <div class="flex items-center lg:order-2 gap-2">
                {{-- Dark Mode Toggle --}}
                <button @click="dark = !dark" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200">
                    <svg x-show="!dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg x-show="dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                    <span class="sr-only">Open user menu</span>
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-medium">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>
                <div class="hidden z-50 my-4 w-48 text-base list-none bg-white dark:bg-gray-700 rounded-lg divide-y divide-gray-100 dark:divide-gray-600 shadow" id="dropdown-user">
                    <div class="py-3 px-4">
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                        <span class="block text-sm text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</span>
                    </div>
                    <ul class="py-1 text-gray-700 dark:text-gray-300" aria-labelledby="dropdown-user">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 px-4 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- SIDEBAR --}}
    <aside id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 md:translate-x-0" aria-label="Sidenav">
        <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">
            <ul class="space-y-2">

                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-base font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                @canany(['products.view', 'vendors.view', 'customers.view'])
                <li>
                    <button type="button" class="flex items-center p-2 w-full text-base font-medium text-gray-900 dark:text-white rounded-lg transition duration-75 group hover:bg-gray-100 dark:hover:bg-gray-700" aria-controls="dropdown-master" data-collapse-toggle="dropdown-master">
                        <svg class="flex-shrink-0 w-6 h-6 text-gray-500 dark:text-gray-400 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Master Data</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-master" class="space-y-2 py-2">
                        @can('products.view')
                        <li><a href="{{ route('products.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('products.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Products</a></li>
                        @endcan
                        @can('vendors.view')
                        <li><a href="{{ route('vendors.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('vendors.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Vendors</a></li>
                        @endcan
                        @can('customers.view')
                        <li><a href="{{ route('customers.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('customers.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Customers</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['purchase_requests.view', 'purchase_orders.view', 'goods_receipts.view', 'purchase_invoices.view', 'purchase_returns.view'])
                <li>
                    <button type="button" class="flex items-center p-2 w-full text-base font-medium text-gray-900 dark:text-white rounded-lg transition duration-75 group hover:bg-gray-100 dark:hover:bg-gray-700" aria-controls="dropdown-purchasing" data-collapse-toggle="dropdown-purchasing">
                        <svg class="flex-shrink-0 w-6 h-6 text-gray-500 dark:text-gray-400 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Purchasing</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-purchasing" class="hidden space-y-2 py-2">
                        @can('purchase_requests.view')<li><a href="{{ route('purchase-requests.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('purchase-requests.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Purchase Requests</a></li>@endcan
                        @can('purchase_orders.view')<li><a href="{{ route('purchase-orders.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('purchase-orders.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Purchase Orders</a></li>@endcan
                        @can('goods_receipts.view')<li><a href="{{ route('goods-receipts.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('goods-receipts.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Goods Receipts</a></li>@endcan
                        @can('purchase_invoices.view')<li><a href="{{ route('purchase-invoices.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('purchase-invoices.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Purchase Invoices</a></li>@endcan
                        @can('purchase_returns.view')<li><a href="{{ route('purchase-returns.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('purchase-returns.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Purchase Returns</a></li>@endcan
                    </ul>
                </li>
                @endcanany

                @canany(['sales_orders.view', 'delivery_orders.view', 'sales_invoices.view', 'sales_returns.view', 'receipts.view'])
                <li>
                    <button type="button" class="flex items-center p-2 w-full text-base font-medium text-gray-900 dark:text-white rounded-lg transition duration-75 group hover:bg-gray-100 dark:hover:bg-gray-700" aria-controls="dropdown-sales" data-collapse-toggle="dropdown-sales">
                        <svg class="flex-shrink-0 w-6 h-6 text-gray-500 dark:text-gray-400 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                        </svg>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Sales</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-sales" class="hidden space-y-2 py-2">
                        @can('sales_orders.view')<li><a href="{{ route('sales-orders.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('sales-orders.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Sales Orders</a></li>@endcan
                        @can('delivery_orders.view')<li><a href="{{ route('delivery-orders.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('delivery-orders.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Delivery Orders</a></li>@endcan
                        @can('sales_invoices.view')<li><a href="{{ route('sales-invoices.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('sales-invoices.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Sales Invoices</a></li>@endcan
                        @can('sales_returns.view')<li><a href="{{ route('sales-returns.index') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('sales-returns.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Sales Returns</a></li>@endcan
                    </ul>
                </li>
                @endcanany

                @can('receipts.view')
                <li>
                    <a href="{{ route('receipts.index') }}" class="flex items-center p-2 text-base font-medium rounded-lg {{ request()->routeIs('receipts.*') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Receipts</span>
                    </a>
                </li>
                @endcan

                @canany(['reports.purchases', 'reports.sales', 'reports.financial'])
                <li>
                    <button type="button" class="flex items-center p-2 w-full text-base font-medium text-gray-900 dark:text-white rounded-lg transition duration-75 group hover:bg-gray-100 dark:hover:bg-gray-700" aria-controls="dropdown-laporan" data-collapse-toggle="dropdown-laporan">
                        <svg class="flex-shrink-0 w-6 h-6 text-gray-500 dark:text-gray-400 transition duration-75 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Laporan</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-laporan" class="space-y-2 py-2 {{ request()->routeIs('reports.*') ? '' : 'hidden' }}">
                        @can('reports.purchases')<li><a href="{{ route('reports.purchases') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('reports.purchases') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Purchase Reports</a></li>@endcan
                        @can('reports.sales')<li><a href="{{ route('reports.sales') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('reports.sales') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Sales Reports</a></li>@endcan
                        @can('reports.financial')<li><a href="{{ route('reports.financial') }}" class="flex items-center p-2 pl-11 w-full text-base font-medium rounded-lg {{ request()->routeIs('reports.financial') ? 'bg-blue-50 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">Financial Reports</a></li>@endcan
                    </ul>
                </li>
                @endcanany

            </ul>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="p-4 md:ml-64 h-auto pt-20">

        {{-- Page Heading --}}
        @isset($header)
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $header }}</h1>
        </div>
        @endisset

        {{-- Toast --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="fixed top-4 right-4 z-50 flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg">
            <div class="text-sm text-green-700">{{ session('success') }}</div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        @endif

        {{-- Slot --}}
        {{ $slot }}

    </main>

</body>
</html>
