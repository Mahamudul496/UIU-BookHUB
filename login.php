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
<title>Login | UIU BookHUB</title></head><body>

<header class="site-navbar">
  <div class="container nav-inner">
    <a class="brand" href="index.php"><img src="assets/logo.png" alt="UIU BookHUB"></a>
    <button class="menu-toggle" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    <nav class="nav-links">
      <a class="" href="index.php">Home</a>
      <a class="" href="marketplace.php">Marketplace</a>
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
<section class="auth-page"><div class="container"><div class="auth-card">
  <img class="auth-logo" src="assets/logo.png" alt="UIU BookHUB">
  <h1>Welcome Back</h1><p>Login to buy, sell, chat and manage your listings.</p>
  <form id="login-form">
    <div class="login-mode" role="group" aria-label="Choose account type">
      <button class="login-mode-button active" type="button" data-login-mode="student" aria-pressed="true">Student</button>
      <button class="login-mode-button" type="button" data-login-mode="admin" aria-pressed="false">Admin</button>
    </div>
    <div class="form-group"><label id="login-id-label" for="login-id">Student ID</label><input class="form-control" id="login-id" name="student_id" type="text" placeholder="e.g. Use (0112310496)" autocomplete="username" required autofocus></div>
    <div class="form-group" id="login-name-group"><label for="login-name">Your Name <span style="color:#999;font-weight:400;">(first login only)</span></label><input class="form-control" id="login-name" name="full_name" type="text" placeholder="e.g. Mahamudul Hasan" autocomplete="name"></div>
    <div class="form-group" id="admin-pass-group" hidden><label for="admin-password">Admin Password</label><input class="form-control" id="admin-password" name="password" type="password" placeholder="e.g. Admin123" autocomplete="current-password"></div>
    <button class="btn btn-primary" id="login-submit" style="width:100%;" type="submit">Continue</button>
  </form>
  <div class="auth-footer" id="login-help">Students can sign in with their Student ID. New accounts are created automatically.</div>
  <!-- <div style="margin-top:18px;text-align:center;"><a href="student/dashboard.php" style="font-size:11px;color:#777;">Demo: Open Student Dashboard →</a></div> -->
</div></div></section>
<div class="toast"></div><script src="js/pages.js"></script></body></html>