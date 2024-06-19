<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <a href="{{ route('profile.update') }}">
                <div class="p-6 mb-6 bg-white border-b border-gray-200 hover:bg-green-100">
                    <div class="max-w-sm overflow-hidden rounded">
                        <div class="px-6 py-4">
                            <div class="flex items-center">
                                <i class="text-gray-500 fas fa-location-dot"></i>
                                <div class="ml-5 text-xl font-bold">Alamat Pengiriman</div>
                            </div>
                            <h5 class="mt-5 ml-8 text-base text-gray-700">
                                Nama: {{ Auth::user()->name }}
                            </h5>
                            <h5 class="mt-1 ml-8 text-base text-gray-700">
                                Alamat: {{ Auth::user()->address }}
                            </h5>
                        </div>
                    </div>
                </div>
            </a>

            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Count</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $no = 1; ?>
                            @foreach ($carts as $cart)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $no++ }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cart->book->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $cart->count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($cart->book->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $totalPricePerItem = $cart->book->price * $cart->count;
                                        @endphp
                                        Rp. {{ number_format($totalPricePerItem, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('cart.destroy', $cart->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400">
                                                <x-heroicon-o-trash class="w-6 h-6"/>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="px-6 py-4 font-bold whitespace-nowrap">Grand Total</td>
                                <td colspan="2" class="px-6 py-4 font-bold whitespace-nowrap">
                                    @php
                                        $grandTotal = $carts->sum(function ($cart) {
                                            return $cart->book->price * $cart->count;
                                        });
                                    @endphp
                                    Rp. {{ number_format($grandTotal, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-end">
                                        <a href="{{ route('transaction.create') }}" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                            Lanjutkan pembayaran
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
