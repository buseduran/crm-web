<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FenergyTech CRM - Müşteri İlişkileri Yönetimi</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-purple-600 dark:text-purple-400">CRM Yönetim - FenergyTech</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a
                                href="/admin"
                                class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Yönetim Paneli
                            </a>
                        @else
                        <a
                            href="{{ route('login') }}"
                                class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Giriş Yap
                            </a>
                    @endauth
                    </div>
                </div>
            </div>
                </nav>

        <!-- Hero Section -->
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-24">
                <div class="text-center">
                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6">
                        Müşteri İlişkileri Yönetimi
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                        Müşterilerinizi, fırsatlarınızı ve aktivitelerinizi tek bir platformda yönetin.
                    </p>
                    @guest
                        <div class="flex justify-center space-x-4">
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors"
                            >
                                Giriş Yap
                            </a>
                        </div>
                    @endguest
                </div>

                <!-- Features -->
                <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Müşteri Yönetimi</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Tüm müşteri bilgilerinizi merkezi bir yerde toplayın ve yönetin.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Fırsat Takibi</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Satış fırsatlarınızı takip edin ve önceliklendirin.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aktivite Yönetimi</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Müşteri etkileşimlerinizi ve aktivitelerinizi kaydedin.
                        </p>
                    </div>
                </div>
                </div>
            </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                    &copy; {{ date('Y') }} FenergyTech. Tüm hakları saklıdır.
                </p>
            </div>
        </footer>
        </div>
    </body>
</html>
