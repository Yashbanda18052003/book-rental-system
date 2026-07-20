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
        <p>Good news — the book you reserved is now available!</p>

        <div style="background:#f8f9fa; padding:15px; border-radius:8px; margin:20px 0;">
            <strong>Book Title:</strong> {{ $reservation->book->title }}
        </div>

        <div style="background:#fff3cd; border:1px solid #ffe08a; padding:15px; border-radius:8px; margin:20px 0;">
            <strong>⏳ You have 48 hours to claim it.</strong><br>
            Please contact the admin to have it assigned to you before
            {{ $reservation->available_at->addHours(48)->format('d M, Y g:i A') }}.
            If it isn't claimed by then, it will automatically go to the next person in the queue.
        </div>

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