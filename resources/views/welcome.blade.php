<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BabaSultan23</title>
    <style>
        /* Sayfa yüksekliği ve genişliği %100 olmalı */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center; /* Yatayda ortalar */
            align-items: center; /* Dikeyde ortalar */
            background-color: #f4f4f4;
        }

        /* Buton için stil */
        .btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 2px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Buton -->
<a href="{{ route('student.index') }}">
    <button class="btn">Öğrenci Listesine Git</button>
</a>
<a href="{{ route('package.index') }}">
    <button class="btn">Package Test'e Git</button>
</a>
</body>
</html>
