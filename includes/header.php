<?php
// Puedes incluir aquí config si lo necesitas en todo el sitio
// require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AAPP</title>

  <link rel="icon" href="/images/favico.png" type="image/png">

  <link rel="stylesheet" href="/assets/css/nosotros.css">
  <link rel="stylesheet" href="/assets/css/home.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body.menu-open {
      overflow: hidden;
    }

    html {
      margin-top: 0 !important;
    }

    body {
      top: 0 !important;
    }

    /* GOOGLE TRANSLATE */
    .skiptranslate iframe {
      display: none !important;
    }

    .goog-te-banner-frame {
      display: none !important;
    }

    .goog-te-banner-frame.skiptranslate {
      display: none !important;
    }

    .goog-te-gadget-icon {
      display: none !important;
    }

    .goog-logo-link {
      display: none !important;
    }

    .goog-te-gadget {
      font-size: 0px !important;
    }

    #goog-gt-tt,
    .goog-te-balloon-frame {
      display: none !important;
    }

    #google_translate_element {
      display: none;
    }

    /* MOBILE MENU */
    .mobile-menu {
      position: fixed;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: #000;
      color: #fff;
      z-index: 9999;
      transition: 0.3s;
      padding: 20px;
      overflow-y: auto;
    }

    .mobile-menu.active {
      left: 0;
    }

    .mobile-menu a {
      color: #fff;
      display: block;
      padding: 12px 0;
      font-size: 18px;
      text-decoration: none;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .mobile-menu .logo {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .hamburger {
      font-size: 28px;
      cursor: pointer;
      border: none;
      background: none;
    }

    .mobile-submenu-title {
      display: block;
      font-size: 13px;
      letter-spacing: 1px;
      color: rgba(255, 255, 255, 0.5);
      margin-top: 20px;
      margin-bottom: 10px;
      text-transform: uppercase;
    }

    .dropdown-menu {
      border-radius: 14px;
      border: none;
      padding: 10px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
    }

    .dropdown-item {
      border-radius: 10px;
      padding: 10px 14px;
      font-weight: 500;
    }

    .dropdown-item:hover {
      background: #f5f5f5;
    }

    @media (min-width: 992px) {
      .mobile-menu {
        display: none;
      }
    }
  </style>
</head>

<body>

  <!-- GOOGLE TRANSLATE -->
  <div id="google_translate_element"></div>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container">

      <!-- LOGO -->
      <a class="navbar-brand" href="/index.php">
        <img src="/images/logo.png" alt="Logo" style="height:40px;">
      </a>

      <!-- MOBILE BUTTON -->
      <button class="hamburger d-lg-none" onclick="toggleMenu()">
        ☰
      </button>

      <!-- DESKTOP MENU -->
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto align-items-lg-center">

          <!-- ABOUT -->
          <li class="nav-item dropdown">

            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="aboutDropdown"
              onclick="toggleAboutMenu(event)">

              About

            </a>

            <ul class="dropdown-menu" id="aboutDropdownMenu">
              <li>
                <a class="dropdown-item" href="/public/about/">
                  Leadership Team
                </a>
              </li>

              <li>
                <a class="dropdown-item" href="/public/step1-team/">
                  Step 1 Team
                </a>
              </li>

              <li>
                <a class="dropdown-item" href="/public/step2-team/">
                  Step 2 Team
                </a>
              </li>

              <li>
                <a class="dropdown-item" href="/public/step3-team/">
                  Step 3 Team
                </a>
              </li>

              <li>
                <a class="dropdown-item" href="/public/social-media-team/">
                  Social Media Team
                </a>
              </li>

              <li>
                <a class="dropdown-item" href="/public/pediatric-team/">
                  Pediatric Team
                </a>
              </li>

              <li>
                <a class="dropdown-item" href="/public/internal-medicine-team/">
                  Internal Medicine Team
                </a>
              </li>

            </ul>

          </li>

          <li class="nav-item">
            <a class="nav-link" href="/public/residency/">Residency</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/public/application/">Application</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/public/interview/">Interview</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/public/match/">Match</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/public/fellowship/">Fellowship</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/public/pathways/">Pathways</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="/public/contact/">Contact</a>
          </li>

          <li class="nav-item ms-lg-2">
            <a href="https://www.paypal.com/donate/?hosted_button_id=N5GN25Q6LALE4"
              class="btn btn-primary rounded-pill px-4 fw-semibold">
              Donate
            </a>
          </li>

          <!-- LANGUAGE -->
          <li class="nav-item ms-lg-3">
            <div class="d-flex align-items-center gap-2">

              <a href="#"
                onclick="setLanguage('en'); return false;"
                class="btn btn-sm btn-light rounded-pill px-3 border fw-semibold">

                <i class="fa-solid fa-language me-1"></i> EN
              </a>

              <a href="#"
                onclick="setLanguage('es'); return false;"
                class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold">
                ES
              </a>

            </div>
          </li>

        </ul>

      </div>
    </div>
  </nav>

  <!-- MOBILE MENU -->
  <div id="mobileMenu" class="mobile-menu">

    <div class="d-flex justify-content-between align-items-center mb-3">

      <a class="navbar-brand" href="/index.php">
        <img src="/images/logo-blanco.png" alt="Logo" style="height:40px;">
      </a>

      <button class="hamburger text-white" onclick="toggleMenu()">✕</button>

    </div>

    <a href="/index.php">Home</a>

    <!-- ABOUT MOBILE -->
    <a href="#"
      onclick="toggleMobileAbout(event)"
      id="mobileAboutBtn">

      About
    </a>

    <div id="mobileAboutMenu" style="display:none;">
      <a href="/public/about/">Leadership Team</a>
      <a href="/public/step1-team/">Step 1 Team</a>
      <a href="/public/step2-team/">Step 2 Team</a>
      <a href="/public/step3-team/">Step 3 Team</a>
      <a href="/public/social-media-team/">Social Media Team</a>
      <a href="/public/pediatric-team/">Pediatric Team</a>
      <a href="/public/internal-medicine-team/">Internal Medicine Team</a>
    </div>

    <a href="/public/residency/">Residency</a>
    <a href="/public/application/">Application</a>
    <a href="/public/interview/">Interview</a>
    <a href="/public/match/">Match</a>
    <a href="/public/fellowship/">Fellowship</a>
    <a href="/public/pathways/">Pathways</a>
    <a href="/public/contact/">Contact</a>
    <div class="mt-3 mb-3">
      <a href="https://www.paypal.com/donate/?hosted_button_id=N5GN25Q6LALE4"
        class="btn btn-warning w-100 rounded-pill fw-semibold">
        Donate
      </a>
    </div>

    <!-- MOBILE LANGUAGE -->
    <div class="d-flex gap-2 mt-4">

      <a href="#"
        onclick="setLanguage('en'); return false;"
        class="btn btn-light rounded-pill px-4">

        <i class="fa-solid fa-language me-1"></i> EN
      </a>

      <a href="#"
        onclick="setLanguage('es'); return false;"
        class="btn btn-outline-light rounded-pill px-4">
        ES
      </a>

    </div>

  </div>

  <!-- JS -->
  <script>
    function toggleMenu() {
      const menu = document.getElementById("mobileMenu");
      menu.classList.toggle("active");
      document.body.classList.toggle("menu-open");
    }

    function googleTranslateElementInit() {

      new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,es',
        autoDisplay: false
      }, 'google_translate_element');
    }

    function setLanguage(lang) {

      var interval = setInterval(function() {

        var select = document.querySelector(".goog-te-combo");

        if (select) {

          select.value = lang;
          select.dispatchEvent(new Event("change"));

          clearInterval(interval);
        }

      }, 500);
    }
  </script>

  <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function toggleAboutMenu(event) {

      event.preventDefault();

      const menu = document.getElementById("aboutDropdownMenu");

      menu.classList.toggle("show");
    }

    document.addEventListener("click", function(e) {

      const dropdown = document.getElementById("aboutDropdown");
      const menu = document.getElementById("aboutDropdownMenu");

      if (!dropdown.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.remove("show");
      }
    });

    function toggleMobileAbout(event) {

      event.preventDefault();

      const menu = document.getElementById("mobileAboutMenu");

      if (menu.style.display === "none") {

        menu.style.display = "block";

      } else {

        menu.style.display = "none";

      }

    }
  </script>

</body>

</html>