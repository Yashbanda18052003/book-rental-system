<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fines Report</title>
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
    <h2>BookNest - Fines Report</h2>
    <p>Generated on {{ date('d M Y, h:i A') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>User</th>
            <th>Book</th>
            <th>Late Days</th>
            <th>Fine Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($fines as $fine)
            <tr>
                <td>{{ $fine->user->name ?? 'N/A' }}</td>
                <td>{{ $fine->rental->book->title ?? 'N/A' }}</td>
                <td>{{ $fine->late_days }}</td>
                <td>₹{{ number_format($fine->fine_amount, 2) }}</td>
                <td>{{ ucfirst($fine->status) }}</td>
                <td>{{ $fine->created_at ? $fine->created_at->format('d M Y') : 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>Total Records: {{ count($fines) }}</p>
</div>

</body>
</html>
