<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>

    <link rel="stylesheet" href="{{ url('/css/landing-page.css') }}">
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

@include('auth.login')
@include('auth.register')
@include('auth.forgot')

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid"> 
            <div class="logo">
                <img src="Photos/VS_logo.png" alt="">
            </div>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#home">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#service">Service</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#about">About</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

    <!-- HOME SECTION-->
    <section class="home" id="home">
        <div class="home-text">
            <span>Welcome To</span>
            <h1>CENTRAL</h1>
            <h2>STI College Alabang Incident Reporting Portal</h2>
            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#loginModal">Log In</button>
        </div>
        <div class="home-img">
            <img src="Photos/Background.png" alt="">
        </div>
    </section>

    <!-- SERVICE SECTION -->
    <section class="service" id="service">
        <div class="heading">
            <span>Look what our website carry out</span>
            <h1>Our Service</h1>
        </div>
        <br>
        <div class="service_container">
            <!-- <img src="Photos/ServicePic.png" alt=""> -->
            <div class="service-content">

                <h2>Service</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa...</p>
                <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu...</p>
            </div>
            </section>
        </div>
        </section>

    <!-- ABOUT SECTION -->
    <section class="about" id="about">
        <div class="about-img">
            <img src="Photos/BG.jpg" alt="">
        </div>
        <div class="about-text">
            <h2>About</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa...</p>
            <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu...</p>
        </div>
    </section>

      <section class="contact" id="contact">
        <div class="social">
            <a href="#"><i class='bx bxl-facebook'></i></a>
            <a href="#"><i class='bx bxl-twitter'></i></a>
            <a href="#"><i class='bx bxl-instagram'></i></a>
            <a href="#"><i class='bx bxl-youtube'></i></a>
        </div>
        <div class="links">
            <a href="#">Privacy Policy</a>
            <a href="#">Term Of Use</a>
            <a href="#">Our School</a>
        </div>
        <p>&#169; Central All Right Reserved.</p>
    </section>

    
    <script>
          let menu = document.querySelector('#menu-icon');
          let navbar = document.querySelector('.navbar');

        // window.onscroll = () => {
        //     menu.classList.remove('bx-x');
        //     navbar.classList.remove('active');
        // }
    </script>

</body>
</html>