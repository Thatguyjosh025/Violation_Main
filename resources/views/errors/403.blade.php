<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 Forbidden</title>
  <link rel="stylesheet" href="ErrorPage.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container-fluid">
    <div class="row min-vh-100">

      <div class="col-lg-6 col-12 left-side">
        <h1>403</h1>
        <h2>Oops! Access denied</h2>
        <p>You don’t have permission to view this page.</p>
        <a href="{{ url('/') }}" class="">Back to homepage</a>
        <img src="{{ asset('./Photos/403-error.png') }} " alt="Central Logo" class="logo">
      </div>
      <div class="col-lg-6 col-12 right-side">
        <img src="{{ asset('./Photos/403-error.png') }}" alt="">
      </div>
    </div>
  </div>
</body>
<style> 
        body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
    }
    /*Right Side Error Image*/
    .right-side {
      background-color: #2596BE;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }

    .right-side img {
      max-width: 80%;
      height: auto;
    }
    /*Left Side Error Text*/
    .left-side {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 2rem;
      position: relative;
    }

    .left-side h1 {
      font-size: 6rem;
      font-weight: bold;
    }

    .left-side h2 {
      font-size: 1.8rem;
      margin-top: 1rem;
      font-weight: 500;
    }

    .left-side p {
      margin: 1rem 0;
      color: #888;
    }
    .logo {
      position: absolute;
      top: 20px;
      left: 20px;
      width: 120px;
      height: auto;
    }
    .btn-custom {
      background-color: #2596BE;
      color: white;
      padding: 10px 25px;
      border-radius: 5px;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-custom:hover {
      background-color: #444;
      color: #fff;
    }

    /* Page Responsiveness */
    @media (max-width: 992px) {
      .left-side h1 {
        font-size: 4rem;
      }
    }

    @media (max-width: 768px) {
      .left-side h1 {
        font-size: 3rem;
      }

      .left-side h2 {
        font-size: 1.4rem;
      }
      .logo {
        width: 90px;
      }
    }
</style>
</html>