<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    /* CSS untuk alert */
    #alertBox {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    /* CSS untuk informasi penerima */
    .recipient-info {
        margin-top: 1.5rem;
        padding: 1rem;
        background-color: #f3f4f6;
        border-radius: 0.5rem;
    }

    .recipient-info h3 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #4b5563;
        margin-bottom: 0.5rem;
    }

    .recipient-info .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .recipient-info .info-item i {
        font-size: 1rem;
        color: #6b7280;
        margin-right: 0.5rem;
    }

    .recipient-info .info-item span {
        font-size: 1rem;
        color: #4b5563;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Informasi Penerima -->
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-8 mb-4 bg-white recipient-info">
                        <h3 class="mb-4">Customer Information</h3>
                        <div class="mb-2">
                            <span>Nama</span>
                            <div class="flex items-center info-item">
                                <i class="mr-2 text-gray-500 fas fa-user fa-sm"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span>Alamat</span>
                            <div class="flex items-center info-item">
                                <i class="mr-2 text-gray-500 fas fa-map-marker-alt fa-sm"></i>
                                <span>{{ Auth::user()->address }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Detail Belanja -->
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
                                    <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($cart->book->price * $cart->count, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="px-6 py-4 font-bold whitespace-nowrap">Total Pembayaran</td>
                                <td colspan="2" class="px-6 py-4 font-bold whitespace-nowrap">Rp. {{ number_format($totalPrice, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form untuk Upload Bukti Pembayaran dan Checkout -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <h5 class="mb-2 text-base font-medium text-gray-700">
                            Silakan Lakukan Pembayaran Sebesar <strong class="text-xl font-bold">Rp. {{ number_format($totalPrice, 2) }}</strong> ke Nomor Rekening:
                        </h5>
                        <p class="text-lg font-semibold text-gray-900">0166-01-020870-53-8</p>
                    </div>
                    <form method="POST" action="{{ route('transaction.checkout') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="max-w-md mt-4">
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700">Upload Bukti Pembayaran</label>
                            <div class="mt-1">
                                <input type="file" id="payment_proof" name="payment_proof" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 font-bold text-white bg-green-500 rounded">Checkout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
