<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Inactive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #dc3545;
        }
        p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        a.button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Account Inactive</h1>
        <p>Your account is inactive. Please contact support for assistance.</p>
        <a href="{{ url('/') }}" class="button">Go Back to Landing Page</a>    
    </div>
</body>
</html>
