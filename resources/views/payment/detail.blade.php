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

    /* CSS untuk gambar bukti pembayaran */
    .payment-proof img {
        max-width: 300px;
        height: auto;
        margin-top: 1rem;
        border-radius: 0.5rem;
    }

    /* CSS untuk informasi pelanggan */
    .customer-info {
        margin-top: 1.5rem;
        padding: 1rem;
        background-color: #e5e7eb;
        border-radius: 0.5rem;
    }

    .customer-info h3 {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        color: white;
    }

    .customer-info .info-item {
        margin-bottom: 0.75rem;
    }

    .customer-info .info-item strong {
        display: inline-block;
        width: 150px;
        color: #a0aabd;
    }

    .customer-info .info-item span {
        color: #a0aabd;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Transaction Detail') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Informasi Pemesan -->
                    <div class="mb-6 text-gray-700 customer-info bg-gray-50 dark:bg-gray-700 ">
                        <h3 class="text-lg">{{ __('Customer Information') }}</h3>
                        <div class="info-item">
                            <strong>{{ __('Name') }}:</strong> <span>{{ $payment->user->name }}</span>
                        </div>
                        <div class="info-item">
                            <strong>{{ __('Phone Number') }}:</strong> <span>{{ $payment->user->phone_number }}</span>
                        </div>
                        <div class="info-item">
                            <strong>{{ __('Address') }}:</strong> <span>{{ $payment->user->address }}</span>
                        </div>
                    </div>

                    <!-- Detail Pesanan -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold">{{ __('Order Details') }}</h3>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">{{ __('Book Title') }}</th>
                                    <th class="px-6 py-3">{{ __('Count') }}</th>
                                    <th class="px-6 py-3">{{ __('Total Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payment->orders as $order)
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="px-6 py-4">{{ $order->book->title }}</td>
                                        <td class="px-6 py-4">{{ $order->count }}</td>
                                        <td class="px-6 py-4">Rp. {{ number_format($order->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Total Keseluruhan -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold">{{ __('Total Price') }}</h3>
                        <p>Rp. {{ number_format($payment->total_price, 2) }}</p>
                    </div>

                    <!-- Bukti Pembayaran -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold">{{ __('Payment Proof') }}</h3>
                        @if ($payment->payment_proof)
                            <div class="payment-proof">
                                <img src="{{ Storage::url($payment->payment_proof) }}" alt="Payment Proof">
                            </div>
                        @else
                            <p><em>Menunggu Pembayaran</em></p>
                        @endif
                    </div>

                    <!-- Nomor Resi -->
                    @if(Auth::user()->is_admin)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold">{{ __('Tracking Number') }}</h3>
                        <form method="POST" action="{{ route('payment.updateTrackingNumber', $payment->id) }}">
                            @csrf
                            <div class="flex items-center">
                                <input type="text" name="tracking_number" id="tracking_number" class="block mt-2 text-white bg-gray-700 form-input" value="{{ $payment->tracking_number }}" required autofocus />
                                <button type="submit" class="ml-4 btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold">{{ __('Tracking Number') }}</h3>
                        @if($payment->tracking_number)
                            <p>{{ $payment->tracking_number }}</p>
                        @else
                            <p><em>Pesanan belum diproses</em></p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
