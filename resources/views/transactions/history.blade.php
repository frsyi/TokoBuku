<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Transaction History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">


            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID Order</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tracker Number</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $no = 1; ?>
                            @forelse ($transactions as $transaction)
                            <tr>
                                @if(Auth::check() && Auth::user()->role == 'admin')
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->order_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->updated_at }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->payment_method }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($transaction->total_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->payment_status }}</td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->order_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($transaction->total_price, 2) }}</td>
                                    <td></td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->payment_status }}</td>
                                @endif
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap">No transactions found</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
