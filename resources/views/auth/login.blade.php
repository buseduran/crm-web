@extends('layouts.auth')

@section('title', 'Giriş Yap')

@section('content')
<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">CRM Yönetim Sistemi</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Hesabınıza giriş yapın</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                E-posta Adresi
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="email"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                placeholder="ornek@email.com"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Şifre
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                placeholder="••••••••"
            >
            @error('password')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 dark:border-gray-600 dark:bg-gray-700"
                >
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Beni hatırla</span>
            </label>

            <a
                href="{{ route('password.request') }}"
                class="text-sm text-purple-600 hover:text-purple-500 dark:text-purple-400 dark:hover:text-purple-300"
            >
                Şifremi unuttum
            </a>
        </div>

        <div>
            <button
                type="submit"
                class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200"
            >
                Giriş Yap
            </button>
        </div>
    </form>

    <div class="text-center">
        <a
            href="{{ route('welcome') }}"
            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
        >
            ← Ana Sayfaya Dön
        </a>
    </div>
</div>
@endsection

