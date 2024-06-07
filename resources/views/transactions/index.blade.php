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
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Transaction') }}
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
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID Order</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No Rekening</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transactions->first()->order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transactions->first()->order->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transactions->first()->status }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">0166-01-020870-53-8</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $no = 1; ?>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $no++ }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->book->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($transaction->book->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($transaction->total_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('order.destroy', $transaction->id) }}" method="POST">
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
                                <td class="px-6 py-4 font-bold whitespace-nowrap">Rp{{ number_format($order->total_price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-end">
                                        <button id="checkoutBtn" type="button" class="px-4 py-2 font-bold text-white bg-green-500 rounded">
                                            Checkout
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="alertBox"></div>
            <div id="checkoutModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
                <div class="p-8 bg-white rounded shadow-lg">
                    <p class="mb-4">Apakah Anda sudah membayar?</p>
                    <div class="flex justify-end">
                        <button id="yesBtn" class="px-4 py-2 mr-4 text-white bg-green-500 rounded">Ya</button>
                        <button id="noBtn" class="px-4 py-2 text-white bg-red-500 rounded">Tidak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for checkout confirmation -->
    <script>
        // JavaScript untuk checkout confirmation
        var checkoutBtn = document.getElementById('checkoutBtn');
        var checkoutModal = document.getElementById('checkoutModal');
        var yesBtn = document.getElementById('yesBtn');
        var noBtn = document.getElementById('noBtn');

        checkoutBtn.addEventListener('click', function() {
        checkoutModal.classList.remove('hidden');
    });

    yesBtn.addEventListener('click', function() {
        checkoutModal.classList.add('hidden');
        showAlert('Terima kasih! Pesanan Anda sedang diproses.');
        // Setelah alert muncul, kembali ke halaman dashboard
        setTimeout(function() {
            window.location.href = "{{ route('dashboard') }}";
        }, 3000); // Tunggu 3 detik sebelum kembali
    });

    noBtn.addEventListener('click', function() {
        checkoutModal.classList.add('hidden');
        showAlert('Silakan selesaikan pembayaran Anda sebelum melanjutkan.');
        // Setelah alert muncul, kembali ke halaman dashboard
        setTimeout(function() {
            window.location.href = "{{ route('dashboard') }}";
        }, 3000); // Tunggu 3 detik sebelum kembali
    });

    // Function untuk menampilkan alert
    function showAlert(message) {
        var alertBox = document.getElementById('alertBox');
        alertBox.innerText = message;
        alertBox.style.display = 'block';
        setTimeout(function() {
            alertBox.style.display = 'none';
        }, 3000); // Durasi alert muncul (ms)
    }
    </script>
</x-app-layout>
