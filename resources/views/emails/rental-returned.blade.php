<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>

<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:20px;">

<div style="max-width:600px; margin:auto; background:white; border-radius:10px; overflow:hidden;">

    <div style="background:#212529; color:white; text-align:center; padding:25px;">
        <h1 style="margin:0;">📚 BookNest</h1>
        <p style="margin-top:10px;">Online Book Rental & Reservation Platform</p>
    </div>

    <div style="padding:30px;">
        <h3>Hello {{ $rental->user->name }},</h3>
        <p>We've received your return of the book below. Thanks for renting with BookNest!</p>

        <div style="background:#f8f9fa; padding:15px; border-radius:8px; margin:20px 0;">
            <strong>Book Title:</strong> {{ $rental->book->title }}<br><br>
            <strong>Rental Period:</strong>
            {{ \Carbon\Carbon::parse($rental->start_date)->format('d M, Y') }}
            &ndash;
            {{ \Carbon\Carbon::parse($rental->end_date)->format('d M, Y') }}<br><br>
            <strong>Returned On:</strong> {{ \Carbon\Carbon::parse($rental->returned_at)->format('d M, Y') }}
        </div>

        @if($rental->fine > 0)
            <div style="background:#fff3cd; border:1px solid #ffe08a; padding:15px; border-radius:8px; margin:20px 0;">
                <strong>Late Return Fine:</strong> ₹{{ $rental->fine }}<br>
                A separate email with payment details for this fine has been sent to you.
            </div>
        @endif

        <p>We hope you enjoyed the book. Browse our catalog for your next read!</p>

        <div style="text-align:center; margin-top:30px;">
            <a href="{{ url('/books') }}"
               style="background:#0d6efd; color:white; text-decoration:none; padding:12px 25px; border-radius:5px; display:inline-block;">
                Browse Books
            </a>
        </div>

        <hr style="margin-top:30px;">
        <p style="font-size:13px; color:#777; text-align:center;">
            Thank you for using BookNest.<br>Happy Reading 📖
        </p>
    </div>

</div>

</body>
</html>
