<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

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
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold">{{ __('Customer Information') }}</h3>
                        <p><strong>{{ __('Name') }}:</strong> {{ $payment->user->name }}</p>
                        <p><strong>{{ __('Phone Number') }}:</strong> {{ $payment->user->phone_number }}</p>
                        <p><strong>{{ __('Address') }}:</strong> {{ $payment->user->address }}</p>
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
                            <p>{{ $payment->tracking_number }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
