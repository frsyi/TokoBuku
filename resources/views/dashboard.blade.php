<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-indigo-500 shadow dark:bg-gray-800">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                        <div class="px-6 pt-6 mb-5 md:w-1/2 2xl:w-1/3">
                            @if (request('search'))
                                <h2 class="pb-3 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                                    Search results for: {{ request('search') }}
                                </h2>
                            @endif
                            <form class="flex items-center gap-2" method="GET" action="{{ route('dashboard') }}">
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
                                                        @auth
                                                            @if(auth()->user()->is_admin)
                                                                <form action="{{ route('payment.index', $book->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                                </form>
                                                            @else
                                                                <form action="{{ route('payment.index', $book->id) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                                    <x-order-button :href="route('order.show', ['id' => $book->id])" class="nav-link">
                                                                        <i class="fas fa-shopping-cart"></i>
                                                                    </x-order-button>
                                                                </form>
                                                            @endif
                                                        @endauth
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
        </main>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert2 Notification Script -->
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>
</html>
