<!-- resources/views/book/show.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Book Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold">{{ $book->title }}</h1>
                    <p class="text-gray-600">{{ $book->author }}</p>
                    <p class="text-gray-600">Publication Year: {{ $book->publication_year }}</p>
                    <p class="text-gray-600">Price: {{ $book->price }}</p>
                    <!-- Add more book details as needed -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
