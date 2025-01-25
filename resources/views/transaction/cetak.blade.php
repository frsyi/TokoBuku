<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions History</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Transactions History</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Order</th>
                <th>Order Date</th>
                <th>Name</th>
                <th>Item Count</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                <td>{{ array_sum(array_column($transaction->items, 'count')) }}</td>
                <td>Rp. {{ number_format($transaction->total_price, 2) }}</td>
                <td>{{ $transaction->order_status ? 'Delivered' : 'Processed' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
