<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>

<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:20px;">

<div style="max-width:600px; margin:auto; background:white; border-radius:10px; overflow:hidden;">

    <div style="background:#212529; color:white; text-align:center; padding:25px;">

        <h1 style="margin:0;">
            📚 BookNest
        </h1>

        <p style="margin-top:10px;">
            Online Book Rental & Reservation Platform
        </p>

    </div>

    <div style="padding:30px;">

        <h3>
            Hello {{ $reservation->user->name }},
        </h3>

        <p>
            Great news! Your reserved book is now available.
        </p>

        <div style="background:#f8f9fa; padding:15px; border-radius:8px; margin:20px 0;">

            <strong>Book Title:</strong><br>

            {{ $reservation->book->title }}

        </div>

        <p>
            Please login to your BookNest account, go to "My Reservations", and click the "Pay Now" button to choose your rental dates and complete the payment.
        </p>

        <div style="text-align:center; margin-top:30px;">

            <a href="{{ url('/login') }}"
               style="
               background:#f0ad4e;
               color:white;
               text-decoration:none;
               padding:12px 25px;
               border-radius:5px;
               display:inline-block;">
                Login to BookNest
            </a>

        </div>

        <hr style="margin-top:30px;">

        <p style="font-size:13px; color:#777; text-align:center;">

            Thank you for using BookNest.

            <br>

            Happy Reading 📖

        </p>

    </div>

</div>

</body>
</html>