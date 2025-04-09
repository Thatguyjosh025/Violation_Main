<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>this is superadmin</h1>

    <div class="div">
        <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
            @csrf
            </form>
        <button onclick="document.getElementById('logout-form').submit();" class="btn btn-danger">
            Logout
        </button>
    </div>
   
</body>
</html>