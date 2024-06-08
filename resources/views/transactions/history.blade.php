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
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Confirmation</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700">
                            <?php $no = 1; ?>
                            @forelse ($orders as $order)
                            <tr class="bg-white dark:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $no++ }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at }}</td>
                                @if(Auth::check() && Auth::user()->is_admin)
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->transactions->sum('amount') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp. {{ number_format($order->total_price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->status ? 'Completed' : 'Pending' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->status ? 'Received' : 'Not Received' }}</td>
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
