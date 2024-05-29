<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Book Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="flex flex-col p-6 text-gray-900 dark:text-gray-100 md:flex-row">
                    <div class="mb-4 md:w-1/4 md:mb-0">
                        @if ($book->image)
                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="w-full h-auto rounded-md" style="max-width: 200px;">
                        @else
                            <img src="{{ asset('images/default_book_image.jpg') }}" alt="{{ $book->title }}" class="w-full h-auto rounded-md" style="max-width: 200px;">
                        @endif
                    </div>
                    <div class="md:w-3/4 md:pl-6">
                        <h1 class="text-2xl font-bold">{{ $book->title }}</h1>
                        <p class="text-gray-600">{{ $book->author }}</p><br>
                        <p class="text-gray-600">Publication Year: {{ $book->publication_year }}</p>
                        <p class="text-gray-600">Category: {{ $book->category->name ?? 'N/A' }}</p>
                        <p class="text-gray-600">Price: ${{ $book->price }}</p>
                        <p class="text-gray-600">Description: {{ $book->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
