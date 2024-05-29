<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Order') }}
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
                        <table class="table mt-3">
                            <tbody>
                                <tr class="text-gray-600 align-top">
                                    <td class="min-w-[150px]">Author</td>
                                    <td class="px-2">:</td>
                                    <td>{{ $book->author }}</td>
                                </tr>
                                <tr class="text-gray-600 align-top">
                                    <td class="min-w-[150px]">Publication Year</td>
                                    <td class="px-2">:</td>
                                    <td>{{ $book->publication_year }}</td>
                                </tr>
                                <tr class="text-gray-600 align-top">
                                    <td class="min-w-[150px]">Category</td>
                                    <td class="px-2">:</td>
                                    <td>{{ $book->category->name ?? 'N/A' }}</td>
                                </tr>
                                <tr class="text-gray-600 align-top">
                                    <td class="min-w-[150px]">Price</td>
                                    <td class="px-2">:</td>
                                    <td>{{ $book->price }}</td>
                                </tr>
                                <tr class="text-gray-600 align-top">
                                    <td class="min-w-[150px]">Description</td>
                                    <td class="px-2">:</td>
                                    <td>{{ $book->description }}</td>
                                </tr>
                                <form action="" method="POST">
                                    <tr>
                                        <td>Jumlah Pesan</td>
                                        <td>:</td>
                                        <td>
                                            <x-text-input id="amount" name="amount" type="text" class="block mt-2" required autofocus autocomplete="amount" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <x-order-button href="{{ route('category.create') }}" />
                                        </td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
