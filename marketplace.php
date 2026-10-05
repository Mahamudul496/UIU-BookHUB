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
<title>Marketplace | UIU BookHUB</title></head><body>

<header class="site-navbar">
  <div class="container nav-inner">
    <a class="brand" href="index.php"><img src="assets/logo.png" alt="UIU BookHUB"></a>
    <button class="menu-toggle" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    <nav class="nav-links">
      <a class="" href="index.php">Home</a>
      <a class="active" href="marketplace.php">Marketplace</a>
      <a href="index.php#exploreDept">Categories</a>
      <a href="index.php#how-it-works">How It Works</a>
    </nav>
    <div class="nav-actions">
      <a href="login.php" class="icon-link" aria-label="Wishlist"><i class="fa-regular fa-heart"></i><span class="count">3</span></a>
      <a href="login.php" class="btn btn-outline login-link">Login</a>
      <a href="login.php" class="btn btn-primary">Get Started</a>
    </div>
  </div>
</header>
<section class="page-head"><div class="container"><div class="breadcrumb"><a href="index.php">Home</a> / Marketplace</div><h1>Browse Marketplace</h1><p>Find affordable textbooks, notes and course materials from UIU students.</p></div></section>
<section class="section"><div class="container">
  <div class="market-layout">
    <aside class="filter-box">
      <h3>Filter Listings</h3>
      <div class="filter-group"><label>Department</label><select><option>All Departments</option><option>CSE</option><option>EEE</option><option>BBA</option><option>Economics</option><option>English</option><option>GED</option></select></div>
      <div class="filter-group"><label>Course Code</label><input type="text" placeholder="e.g. CSE 311"></div>
      <div class="filter-group"><label>Condition</label><label><input type="checkbox"> Like New</label><label><input type="checkbox"> Good</label><label><input type="checkbox"> Used</label></div>
      <div class="filter-group"><label>Type</label><label><input type="checkbox"> Textbook</label><label><input type="checkbox"> Notes</label><label><input type="checkbox"> Manual</label></div>
      <button class="btn btn-primary" style="width:100%;">Apply Filters</button>
    </aside>
    <main>
      <div class="market-top">
        <div class="market-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search title, course code or subject..."></div>
        <div class="sort"><select><option>Newest First</option><option>Price: Low to High</option><option>Price: High to Low</option></select></div>
      </div>
      <p style="margin:0 0 14px;"><a class="btn btn-secondary btn-sm" href="donation.php"><i class="fa-solid fa-heart"></i> Donate a Book</a></p>
      <p style="font-size:12px;color:#777;margin-bottom:16px;"><span id="market-count">Loading…</span></p>
      <div class="grid grid-3" id="market-grid"></div>
      
    </main>
  </div>
</div></section>

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