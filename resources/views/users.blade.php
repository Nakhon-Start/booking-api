<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>test</h1>
<table border="1">

    @foreach ($collection as $item)
    <tr>
        <tr>{{$item['id']}}</tr>
        <tr>{{$item['first_name']}}</tr>
        <tr>{{$item['email']}}</tr>
    </tr>
    @endforeach
</table>
</body>
</html>