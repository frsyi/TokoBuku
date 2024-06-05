<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm overflow-y: auto dark:bg-gray-800 sm:rounded-lg" style="overflow-y: auto;">
                <div class="bg-white">
                    <div class="max-w-2xl px-4 py-16 mx-auto sm:px-6 sm:py-10 lg:max-w-7xl lg:px-8">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Related Books</h2>
                            <form class="flex items-center gap-2" method="GET" action="{{ route('catalogue.index') }}">
                                <x-text-input id="search" name="search" type="text" class="w-full" placeholder="Search by title, author, publication year, or category" value="{{ request('search') }}" autofocus />
                                <x-primary-button type="submit">
                                    {{ __('Search') }}
                                </x-primary-button>
                            </form>
                        </div>

                        <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                            @foreach($related_books as $book)
                            <div class="col-md-3">
                                <div class="h-full card" style="width: 18rem;">
                                    <a href="{{ route('book.show', $book->id) }}">
                                        <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 300px; object-fit: cover;">
                                    </a>
                                    <div class="flex flex-col justify-between card-body">
                                        <div>
                                            <h5 class="text-lg font-bold card-title">{{ $book->title }}</h5>
                                            <p class="card-text">
                                                @if ($book->category)
                                                {{ $book->category->name }}
                                                @endif
                                            </p>
                                            <p class="card-text">{{ $book->author }}</p>
                                        </div>
                                        <div class="mt-4">
                                            <p class="font-bold card-text">${{ number_format($book->price, 2) }}</p>
                                            <form action="{{ route('orders.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                <button type="submit" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md dark:bg-gray-200 dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                                    <i class="fas fa-shopping-cart"></i> Order
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
