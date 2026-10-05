<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
<script defer src="js/api.js"></script>
<script defer src="js/main.js"></script>
<title>Product Details | UIU BookHUB</title></head><body>

<header class="site-navbar">
  <div class="container nav-inner">
    <a class="brand" href="index.php"><img src="assets/logo.png" alt="UIU BookHUB"></a>
    <button class="menu-toggle" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    <nav class="nav-links">
      <a class="" href="index.php">Home</a>
      <a class="active" href="marketplace.php">Marketplace</a>
      <a href="marketplace.php">Categories</a>
      <a href="index.php#how-it-works">How It Works</a>
    </nav>
    <div class="nav-actions">
      <a href="login.php" class="icon-link" aria-label="Wishlist"><i class="fa-regular fa-heart"></i><span class="count">3</span></a>
      <a href="login.php" class="btn btn-outline login-link">Login</a>
      <a href="login.php" class="btn btn-primary">Get Started</a>
    </div>
  </div>
</header>
<section class="section"><div class="container">
  <div class="breadcrumb" style="margin-bottom:22px;"><a href="marketplace.php" style="color:#FF6B00;">Marketplace</a> / <span id="detail-breadcrumb">Product Details</span></div>
  <div id="product-details"><p>Loading listing...</p></div>
</div></section>
<section class="section" style="padding-top:0;"><div class="container"><div id="seller-reviews"></div></div></section>
<section class="section" style="background:#FAFAFA;padding-top:55px;"><div class="container"><div class="section-header"><div><h2>More Listings</h2><p>Other books you may like.</p></div></div><div class="grid grid-4" id="more-listings"></div></div></section>

<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <img class="footer-logo" src="assets/logo.png" alt="UIU BookHUB">
        <p>A simple student-to-student marketplace for affordable textbooks, notes and course materials at UIU.</p>
        <div class="social">
          <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="#" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
        </div>
      </div>
      <div><h4>Marketplace</h4><ul>
        <li><a href="marketplace.php">Browse Books</a></li>
        <li><a href="marketplace.php">Notes & Manuals</a></li>
        <li><a href="marketplace.php">Categories</a></li>
      </ul></div>
      <div><h4>Account</h4><ul>
        <li><a href="login.php">Login</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="login.php">Wishlist</a></li>
      </ul></div>
      <div><h4>Help</h4><ul>
        <li><a href="index.php#how-it-works">How It Works</a></li>
        <li><a href="#" data-toast="Demo support page">Contact Admin</a></li>
        <li><a href="#" data-toast="Demo report option">Report Listing</a></li>
      </ul></div>
    </div>
    <div class="footer-bottom">© 2026 UIU BookHUB. Built for UIU Students.</div>
  </div>
</footer><div class="toast"></div><script src="js/pages.js"></script></body></html>