<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $name }} - Check out this product on Afriinventions">
    <meta name="keywords" content="{{ $name }}, Afriinventions, Africa-based products">
    <meta name="author" content="Afriinventions">
    <meta property="og:title" content="{{ $name }}">
    <meta property="og:description" content="{{ $name }} - Check out this product on Afriinventions">
    <meta property="og:image" content="{{ $photo }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    <meta property="og:site_name" content="Afriinventions">
    <meta property="og:locale" content="en_US">
    <meta property="og:locale:alternate" content="fr_FR">
    {{-- twitter meta --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@afriinventions">
    <meta name="twitter:creator" content="@afriinventions">
    <meta name="twitter:title" content="{{ $name }}">
    <meta name="twitter:description" content="{{ $name }} - Check out this product on Afriinventions">
    <meta name="twitter:image" content="{{ $photo }}">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:domain" content="{{ url()->current() }}">
    <meta name="twitter:label1" content="Written by">
    {{-- whatsapp --}}
    <meta property="og:site_name" content="Afriinventions">
    <meta property="og:title" content="{{ $name }}">
    <meta property="og:description" content="{{ $name }} - Check out this product on Afriinventions">
    <title>{{ $name }} </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .product-container {
            text-align: center;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .product-photo {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .product-name {
            font-size: 24px;
            margin: 15px 0;
            color: #D26000;
        }

        .download-links a {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
        }

        .download-links a.android {
            background-color: #D26000;
        }

        .download-links a.ios {
            background-color: #007aff;
        }
    </style>
</head>

<body>
    <div class="product-container">
        <img src="{{ $photo }}" alt="{{ $name }}" class="product-photo">
        <h1 class="product-name">{{ $name }}</h1>
        <div class="download-links">
            <a href="{{ $android }}" class="android">Download for Android</a><br>
            <a href="{{ $ios }}" class="ios">Download for iOS</a>
        </div>
    </div>
</body>

</html>
