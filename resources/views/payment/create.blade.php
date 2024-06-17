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
            {{ __('Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-8 mb-4 bg-white recipient-info">
                    <h3 class="mb-4">Informasi Penerima</h3>
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
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $no++ }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->book->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($order->book->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($order->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="px-6 py-4 font-bold whitespace-nowrap">Total Payment</td>
                                <td colspan="2" class="px-6 py-4 font-bold whitespace-nowrap">Rp{{ number_format($payment->total_price, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div>
                        <h5 class="mb-2 text-base font-medium text-gray-700">
                            Silakan Lakukan Pembayaran Sebesar <strong style="font-weight: bold; font-size: 1.25rem;">Rp. {{ number_format($payment->total_price, 2) }}</strong> ke Nomor Rekening:
                        </h5>
                        <p class="text-lg font-semibold text-gray-900">0166-01-020870-53-8</p>
                    </div>
                    <div class="mt-4">
                        <label for="paymentProof" class="block text-sm font-medium text-gray-700">Upload Bukti Pembayaran</label>
                        <div class="mt-1">
                            <input type="file" id="paymentProof" name="paymentProof" class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button id="checkoutBtn" type="button" class="px-4 py-2 font-bold text-white bg-green-500 rounded">
                            Checkout
                        </button>
                    </div>
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
                window.location.href = "{{ route('order.history') }}";
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
