<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Transaction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">


            <div class="p-6 mb-6 bg-white border-b border-gray-200">
                <div class="max-w-sm overflow-hidden rounded">
                    <div class="px-6 py-4">
                        <div class="flex items-center">
                            <i class="text-gray-500 fas fa-location-dot"></i>
                            <div class="ml-5 text-xl font-bold">Alamat Pengiriman</div>
                        </div>
                        <h5 class="mt-5 ml-8 text-base text-gray-700">
                            Nama    :
                        </h5>
                        <h5 class="mt-1 ml-8 text-base text-gray-700">
                            Alamat    :
                        </h5>
                    </div>
                </div>
            </div>

            <div class="mb-5 overflow-hidden bg-white shadow-sm sm:rounded-lg ">

                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID Order</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transactions->first()->order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transactions->first()->order->created_at->format('Y-m-d') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                

            </div>


            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">


                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Unit Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->book_title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($transaction->order->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($transaction->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>


                    </table>


                </div>
            </div>
        </div>
    </div>

</x-app-layout>
