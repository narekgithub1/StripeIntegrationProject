<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
<body class="antialiased">
<div>
    @foreach($flights as $item )
        <h1>{{$item->id}}</h1>
        <h1>{{$item->created_at}}</h1>
        @endforeach
</div>
<script>

</script>
</body>
</html>
