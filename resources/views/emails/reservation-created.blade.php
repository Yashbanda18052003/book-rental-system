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
        <h3>Hello {{ $reservation->user->name }},</h3>
        <p>Your reservation has been placed successfully.</p>

        <div style="background:#f8f9fa; padding:15px; border-radius:8px; margin:20px 0;">
            <strong>Book Title:</strong> {{ $reservation->book->title }}<br><br>
            <strong>Queue Position:</strong> {{ $reservation->queue_position }}<br><br>
            <strong>Status:</strong> Waiting
        </div>

        <p>We'll notify you as soon as this book becomes available. You can check your reservation status anytime.</p>

        <div style="text-align:center; margin-top:30px;">
            <a href="{{ url('/my-reservations') }}"
               style="background:#0d6efd; color:white; text-decoration:none; padding:12px 25px; border-radius:5px; display:inline-block;">
                View My Reservations
            </a>
        </div>

        <hr style="margin-top:30px;">
        <p style="font-size:13px; color:#777; text-align:center;">
            Thank you for using BookNest.
        </p>
    </div>

</div>

</body>
</html>