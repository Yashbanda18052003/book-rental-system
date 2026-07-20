<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family:Arial,sans-serif;background:#f5f5f5;padding:20px;">

<div style="max-width:600px;margin:auto;background:#ffffff;padding:30px;border-radius:10px;">

    <div style="text-align:center;">
        <img src="{{ asset('images/booknest-logo.png') }}"
             width="100">
        <h2 style="margin-top:15px;">
            BookNest
        </h2>
    </div>

    <hr>

    <p>Hello {{ $user->name }},</p>

    <p>
        Great news! Your reserved book is now available.
    </p>

    <p>
        <strong>Book:</strong> {{ $book->title }}
    </p>

    <p>
        Please log in to BookNest and rent the book before another user reserves it.
    </p>

    <a href="{{ url('/login') }}"
       style="display:inline-block;
              padding:10px 20px;
              background:#f0ad4e;
              color:white;
              text-decoration:none;
              border-radius:5px;">
        Open BookNest
    </a>

    <hr>

    <small>
        This email was sent automatically by BookNest.
    </small>

</div>

</body>
</html>