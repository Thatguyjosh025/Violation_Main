<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da;
            color: #721c24;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            padding: 2rem;
            border: 1px solid #f5c6cb;
            background-color:rgb(82, 80, 80);
            border-radius: 8px;
            box-shadow: 0 0 10px #f5c6cb;
        }
        a.btn {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forbidden</h1>
        <p>You do not have permission to access this page.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go back to Landing Page</a>
    </div>
</body>
</html>
