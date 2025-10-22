<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>

    <link rel="stylesheet" href="{{ asset('./css/landing-page.css') }}">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

@include('Auth.login')
@include('Auth.register')
@include('Auth.forgot')

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid"> 
            <div class="logo">
                <img src="{{ asset('./Photos/VS_logo.png') }}" alt="">
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
          <h2>STI College Alabang Violation Monitoring Portal</h2>
          <h2 class="tagline" style="font-size: 1.2em; color: #004581; margin-top: 10px; margin-bottom: 20px;">Your Voice Matters: Report Incidents for a Safer and More Responsible Campus Community</h2>
          <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#loginModal">Log In</button>
      </div>
      <div class="home-img">
          <img style="width: 90%; margin-left: 10%;" src="Photos/violation-picture-landing.png" alt="">
      </div>
  </section>

    <!-- SERVICE SECTION -->
    <section class="service" id="service">
      <div class="heading">
          <span>Explore the Features of Our Violation Reporting System</span>
          <!-- <h1>Our Features</h1> -->
      </div>
      <br>
      <div class="service_container">
          <!-- <img src="Photos/ServicePic.png" alt=""> -->
          <div class="service-content">
              <h2>Reporting Violations</h2>
              <p>Our platform allows faculty, and staff to report various types of violations, including academic dishonesty, misconduct, safety issues, and more. Your reports help us maintain a fair and accountable campus environment.</p>

              <h2>Easy and Secure Reporting</h2>
              <p>Submit your reports easily through our user-friendly interface. All reports are securely stored and handled with the utmost confidentiality. Your identity will be protected throughout the process.</p>

              <h2>Track Your Reports</h2>
              <p>After submitting a report, you can track its status. Stay informed about the progress of your report, from submission to resolution. We keep you updated every step of the way.</p>

              <h2>Admin and Support</h2>
              <p>Our dedicated Disciplinary Officer reviews each report thoroughly and takes appropriate action. If you need assistance, our office is always ready to help you with any questions or concerns.</p>
          </div>
      </div>
  </section>

    <!-- ABOUT SECTION needs to adjust the responsiveness tommorow -->
    <section class="about" id="about">
      <div class="about-img">
          <img src="Photos/design2.png" alt="">
      </div>
      <div class="about-text">
          <h2 class="h mt-5">About Our Violation Reporting System</h2>
          <p>Welcome to the STI College Alabang Incident Reporting Portal, a dedicated platform designed to foster a safe, fair, and accountable campus environment. Our system allows students, faculty, and staff to report various types of violations easily and securely.</p>
          <p>We believe that every voice matters. By reporting incidents, you contribute to maintaining the integrity and safety of our campus community. Our system ensures that all reports are handled with confidentiality and professionalism, and we are committed to taking appropriate action on every report.</p>
          <p>Whether it's academic dishonesty, misconduct, safety issues, or any other violation, your reports help us address concerns promptly and effectively. Together, we can create a campus where everyone feels safe and respected.</p>
      </div>
  </section>

      <section class="contact" id="contact">
        <!-- <div class="social">
            <a href="#"><i class='bx bxl-facebook'></i></a>
            <a href="#"><i class='bx bxl-twitter'></i></a>
            <a href="#"><i class='bx bxl-instagram'></i></a>
            <a href="#"><i class='bx bxl-youtube'></i></a>
        </div> -->
        <div class="links">
        <a href="{{ asset('./Privacy Policy.pdf') }}" target="_blank">Privacy Policy</a>            
        <a href="{{ asset('./Terms and Conditions.pdf') }}" target="_blank">Term and Conditions</a>
        <a href="#">Our School</a>
        </div>
        <p>&#169; Central All Right Reserved.</p>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
          let menu = document.querySelector('#menu-icon');
          let navbar = document.querySelector('.navbar');
    </script>

</body>
</html>