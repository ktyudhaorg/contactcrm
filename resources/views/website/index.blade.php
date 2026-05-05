<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    @foreach ($model as $item)
        @php
            $ext = pathinfo($item->attachment, PATHINFO_EXTENSION);
            $url = route('cloud.download', ['path' => $item->attachment]);
        @endphp

        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
            <img src="{{ $url }}" alt="">
        @elseif ($ext === 'pdf')
            <iframe src="{{ $url }}" width="100%" height="500px"></iframe>
        @else
            <a href="{{ $url }}" target="_blank">Download {{ strtoupper($ext) }}</a>
        @endif
    @endforeach
    {{-- <img src="https://drive.google.com/thumbnail?id=1iDZAgJT-B7uQ5J_f-bcc_JppBZnZ9Xk6&sz=w1000a" alt=""> --}}
</body>

</html>
