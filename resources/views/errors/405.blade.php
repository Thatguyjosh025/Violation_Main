<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>405 Method Not Allowed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
</head>
<body>
      <div class="container py-5">
        <div class="alert alert-danger text-center">
        <h2>Oops! Method Not Allowed</h2>
        <p>The request method is not supported for this route.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Go back home</a>
        </div>
    </div>
</body>
</html>