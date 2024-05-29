<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-xl text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <x-create-button href="{{ route('book.create') }}" />
                        </div>
                        <div>
                            @if (session('success'))
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                                    class="text-sm text-green-600 dark:text-green-400">{{ session('success') }}
                                </p>
                            @endif
                            @if (session('danger'))
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                                    class="text-sm text-red-600 dark:text-red-400">{{ session('danger') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Table Books --}}
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Category
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Author
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Publication Year
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Price
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($books as $book)
                                <tr class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700">
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('book.detail', $book) }}"
                                            class="hover:underline">{{ $book->title }}</a>
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        @if ($book->category)
                                            {{ $book->category->name }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $book->author }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $book->publication_year }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $book->price }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-3">
                                            {{-- Edit Button --}}
                                            <a href="{{ route('book.edit', $book) }}" class="text-blue-600 dark:text-blue-400">
                                                <x-heroicon-o-pencil-square class="w-6 h-6"/>
                                            </a>
                                            {{-- Delete Button --}}
                                            <form action="{{ route('book.destroy', $book) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400">
                                                    <x-heroicon-o-trash class="w-6 h-6"/>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white dark:bg-gray-800">
                                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white" colspan="6">
                                        No books found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
