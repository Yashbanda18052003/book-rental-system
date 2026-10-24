<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Subscriptions Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #d4af37; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #121824; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #121824; color: #d4af37; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #888; }
    </style>
</head>
<body>

<div class="header">
    <h2>BookNest - Subscriptions Report</h2>
    <p>Generated on {{ date('d M Y, h:i A') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>User</th>
            <th>Plan</th>
            <th>Price</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($subscriptions as $subscription)
            <tr>
                <td>{{ $subscription->user->name ?? 'N/A' }}</td>
                <td>{{ $subscription->plan->name ?? 'N/A' }}</td>
                <td>₹{{ number_format($subscription->plan->price ?? 0, 2) }}</td>
                <td>{{ ucfirst($subscription->status) }}</td>
                <td>{{ $subscription->start_date }}</td>
                <td>{{ $subscription->end_date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>Total Records: {{ count($subscriptions) }}</p>
</div>

</body>
</html>
