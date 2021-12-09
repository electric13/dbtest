<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DB Test</title>
</head>
<body>
    <p>
        {{ Auth::check() ? "You logged as ".Auth::user()->name : "You aren't logged yet" }}
    </p>
    <p>
        <a href="/login?email=tester100500@mailinator.com&password=fuck1234">Login right</a>
    </p>
    <p>
        <a href="/login?email=tester100500@mailinator.com&password=fuck1235">Login wrong</a>
    </p>
    <p>
        <a href="/logout">Logout</a>
    </p>
</body>
</html>
