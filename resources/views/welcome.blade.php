<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Theme Toggle Switch -->
        <div class="flex justify-end p-4">
            <label for="theme-toggle" class="inline-flex items-center cursor-pointer">
                <div class="ml-2">
                    <i class="text-gray-500 fas fa-sun"></i>
                </div>
                <div class="relative">
                    <input type="checkbox" id="theme-toggle" class="sr-only" x-model="darkMode">
                    <div class="w-10 h-4 bg-gray-300 rounded-full shadow-inner dark:bg-gray-600"></div>
                    <div class="absolute inset-y-0 left-0 w-6 h-6 transition-transform bg-white rounded-full shadow" :class="{ 'translate-x-full': darkMode, 'translate-x-0': !darkMode }"></div>
                </div>
                <div class="ml-2">
                    <i class="text-gray-500 fas fa-moon"></i>
                </div>
            </label>
        </div>

        <!-- Page Content -->
        <main>
            <!-- Hero Section -->
            <section class="py-16 text-center bg-gray-100 dark:bg-gray-900">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">Welcome to {{ config('app.name', 'Laravel') }}</h1>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Your one-stop solution for book discovery</p>
                </div>
            </section>

            <!-- Search and Related Books Section -->
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                        <div class="px-6 pt-6 mb-5 md:w-1/2 2xl:w-1/3">
                            @if (request('search'))
                                <h2 class="pb-3 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                                    Search results for: {{ request('search') }}
                                </h2>
                            @endif
                            <form class="flex items-center gap-2" method="GET" action="{{ route('welcome') }}">
                                <x-text-input id="search" name="search" type="text" class="w-full" placeholder="Search by title, author, publication year, or category" value="{{ request('search') }}" autofocus />
                                <x-primary-button type="submit">
                                    {{ __('Search') }}
                                </x-primary-button>
                            </form>
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-900">
                            <div class="max-w-2xl px-4 py-16 mx-auto sm:px-6 sm:py-10 lg:max-w-7xl lg:px-8">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Related Books</h2>
                                </div>

                                <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                                    @foreach($related_books as $book)
                                        <div class="col-md-3">
                                            <div class="h-full p-4 transition duration-300 transform bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-1" style="width: 18rem;">
                                                <a href="{{ route('book.show', $book->id) }}">
                                                    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="w-full h-auto mb-4 rounded-t-lg" style="object-fit: contain; max-height: 300px;">
                                                </a>
                                                <div class="flex flex-col justify-between card-body">
                                                    <div>
                                                        <h5 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $book->title }}</h5>
                                                        <p class="text-gray-600 dark:text-gray-400">
                                                            @if ($book->category)
                                                                {{ $book->category->name }}
                                                            @endif
                                                        </p>
                                                        <p class="text-gray-600 dark:text-gray-400">{{ $book->author }}</p>
                                                    </div>
                                                    <div class="mt-4">
                                                        <p class="font-bold text-gray-800 dark:text-gray-200">Rp{{ number_format($book->price, 2) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if (request('search'))
                                    <div class="mt-6">
                                        {{ $related_books->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Section -->
            <section id="about" class="py-16 bg-white dark:bg-gray-800">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">About Us</h2>
                    <p class="mt-4 text-gray-600 dark:text-gray-300">
                        We are dedicated to bringing you the best collection of books, spanning various genres and authors. Our mission is to promote reading culture and make books accessible to everyone.
                    </p>
                </div>
            </section>

            <!-- Our Team Section -->
            <section id="team" class="py-16 bg-gray-100 dark:bg-gray-900">
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Our Team</h2>
                    <div class="grid grid-cols-1 gap-8 mt-8 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Team Members -->
                        <div class="text-center">
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Beatrix Devanti Agsi</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">20210140001</p>
                        </div>
                        <div class="text-center">
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Dianitasya Ananda Masta</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">20210140011</p>
                        </div>
                        <div class="text-center">
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Fahma Rosyidah</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">20210140030</p>
                        </div>
                        <div class="text-center">
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Siti Marfungah Dwi Lestari</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">20210140038</p>
                        </div>
                        <div class="text-center">
                            <h3 class="mt-4 text-xl font-semibold text-gray-900 dark:text-gray-100">Sofi Miranda</h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">20210140076</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
