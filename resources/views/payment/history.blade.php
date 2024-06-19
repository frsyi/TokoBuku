<head>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Transactions History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">ID Order</th>
                                <th scope="col" class="px-6 py-3">Order Date</th>
                                @if(Auth::check() && Auth::user()->is_admin)
                                    <th scope="col" class="px-6 py-3">Name</th>
                                @endif
                                <th scope="col" class="px-6 py-3">Item Count</th>
                                <th scope="col" class="px-6 py-3">Total Price</th>
                                @if(!Auth::check() || !Auth::user()->is_admin)
                                    <th scope="col" class="px-6 py-3">Tracking Number</th>
                                @endif
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Confirmation</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700">
                            <?php $no = 1; ?>
                            @forelse ($payments as $payment)
                            <tr class="bg-white cursor-pointer dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700" onclick="window.location='{{ route('payment.show', $payment->id) }}'">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $no++ }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment->created_at }}</td>


                                @if(Auth::check() && Auth::user()->is_admin)
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $payment->user->name }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment->orders->sum('count') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($payment->total_price, 2) }}</td>


                                @if(!Auth::check() || !Auth::user()->is_admin)
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $payment->tracking_number }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                @if ($payment->is_complete == false)
                                    <form action="{{ route('payment.complete', $payment) }}" method="Post">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 dark:text-green-400">
                                            Delivered
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('payment.uncomplete', $todo) }}" method="Post">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-blue-600 dark:text-blue-400">
                                            Pending
                                        </button>
                                    </form>
                                @endif

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $payment->status ? 'Received' : 'Not Received' }}</td>
                            </tr>
                            @empty
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="8" class="px-6 py-4 text-center whitespace-nowrap">No transactions found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
