<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Book Store Form --}}
                    <form method="post" action="{{ route('book.store') }}" enctype="multipart/form-data" class="">
                        @csrf
                        <div class="mb-6">
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="block w-full mt-1"
                                :value="old('title')" required autofocus autocomplete="title" />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="author" :value="__('Author')" />
                            <x-text-input id="author" name="author" type="text" class="block w-full mt-1"
                                :value="old('author')" required autocomplete="author" />
                            <x-input-error class="mt-2" :messages="$errors->get('author')" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="publication_year" :value="__('Publication Year')" />
                            <x-text-input id="publication_year" name="publication_year" type="text" class="block w-full mt-1"
                                :value="old('publication_year')" required autocomplete="publication_year" />
                            <x-input-error class="mt-2" :messages="$errors->get('publication_year')" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" class="block w-full mt-1"
                                :value="old('price')" required autocomplete="price" />
                            <x-input-error class="mt-2" :messages="$errors->get('price')" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block w-full mt-1" required>{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="image" :value="__('Image')" />
                            <x-text-input id="image" name="image" type="file" class="block w-full mt-1" required />
                            <x-input-error class="mt-2" :messages="$errors->get('image')" />
                        </div>
                        <div class="mb-6">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <x-select id="category_id" name="category_id" class="block w-full mt-1">
                                <option value="">Empty</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <a href="{{ route('book.index') }}"
                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
