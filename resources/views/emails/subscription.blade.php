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
        <h3>Hello {{ $subscription->user->name }},</h3>
        <p>Your subscription has been successfully activated.</p>

        <div style="background:#f8f9fa; padding:15px; border-radius:8px; margin:20px 0;">
            <strong>Plan Name:</strong> {{ $plan->name }}<br><br>
            <strong>Price:</strong> ₹{{ $plan->price }}<br><br>
            <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($subscription->start_date)->format('d M, Y') }}<br><br>
            <strong>End Date:</strong> {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M, Y') }}<br><br>
            <strong>Max Active Rentals:</strong> {{ $plan->rental_limit }} books
        </div>

        <p>Enjoy reading with BookNest!</p>

        <div style="text-align:center; margin-top:30px;">
            <a href="{{ url('/my-subscription') }}"
               style="background:#198754; color:white; text-decoration:none; padding:12px 25px; border-radius:5px; display:inline-block;">
                View My Subscription
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
