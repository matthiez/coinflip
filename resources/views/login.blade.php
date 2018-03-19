<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login required - Coinflip</title>
    <noscript>
        <meta http-equiv="refresh" content="0;url=https://www.google.com/search?site=&source=hp&q=how+to+enable+javascript&oq=how+to+enable+javascript" />
    </noscript>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        h2 {
            display: inline-block !important;
        }
        span {
            float: right;
        }
        .login {
            position: absolute;
            top: 35%;
            width: 100%;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 30px; /* Fixed height of the footer */
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<div class='container-fluid'>
    <div class='row text-center'>
        <div class='login'>
            <h1>Coinflip</h1>
            <h2 class='bg-warning'>Login Required!</h2>
            <p>Please sign in through Steam by clicking on the Link below.</p>
            <a href='{{ route('steamlogin') }}' title='Login through Steam'>
                <img src="https://static.csgocards.net/img/sits_01.png" alt="Login through Steam" />
            </a>
        </div>
    </div>
</div>
<footer class="footer">
    <p class="text-muted">
        A Login required to use this Site.<span>Not affiliated with Valve Corp.</span>
    </p>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>