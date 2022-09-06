<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            max-width: 300px;
            margin: auto;
            text-align: center;
            font-family: arial;
        }
    </style>
</head>
<body>

<h2 style="text-align:center">User Profile Card</h2>

<div class="card">
    <h1>{{$user->name}}</h1>
    <h6>{{$user->email}}</h6>
    <p class="title">CEO & Founder, Example</p>
    <p>Harvard University</p>
</div>
</body>
</html>
