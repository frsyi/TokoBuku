<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Unit Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Total Price</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $grandTotal = 0;
                            @endphp
                            @foreach ($orders as $order)
                                @php
                                    $grandTotal += $order->total_price;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->book_title }}</td>
                                    <td class="px-6 py-4 whitespace-nowssrap">{{ $order->amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($order->unit_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($order->total_price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="flex items-center justify-between mt-6">
                        <div class="text-xl font-semibold text-gray-900">Total Payment: Rp{{ number_format($grandTotal, 2) }}</div>
                        <form action="{{ route('payment') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                Lanjutkan pembayaran
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
