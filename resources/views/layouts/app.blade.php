<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande d'absence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .logo-container {
            text-align: center;
            padding: 20px 0;
            background-color: #ffffff;
        }
        .logo-container img {
            height: 100px;
        }
        .content-wrapper {
            background-color: #123a3c;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-top: 30px;
        }
        .btn-primary {
            background-color: #28a745; /* vert */
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
        }
        label {
            font-weight: bold;
        }
        input, select, textarea {
            border-radius: 5px !important;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="{{ asset('images/dgb.png') }}" alt="Logo DGB">
    </div>

    <div class="container content-wrapper">
        @yield('content')
    </div>
</body>
</html>
