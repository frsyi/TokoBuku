<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Book Catalog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="bg-white">
                    <div class="max-w-2xl px-4 py-16 mx-auto sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Related Books</h2>

                        <div class="grid grid-cols-1 mt-6 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                            @foreach($related_books as $book)
                            <div class="relative group">
                                <div class="w-full overflow-hidden bg-gray-200 rounded-md aspect-h-1 aspect-w-1 lg:aspect-none group-hover:opacity-75 lg:h-80">
                                    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="object-cover object-center w-full h-full lg:h-full lg:w-full">
                                </div>
                                <div class="flex justify-between mt-4">
                                    <div>
                                        <h3 class="text-sm text-gray-700">
                                            <a href="{{ route('book.detail', $book->id) }}">
                                                <span aria-hidden="true" class="absolute inset-0"></span>
                                                {{ $book->title }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if ($book->category)
                                            {{ $book->category->name }}
                                            @endif
                                        </p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">${{ $book->price }}</p>
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
