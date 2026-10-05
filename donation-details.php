<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
<script defer src="js/api.js"></script>
<script defer src="js/main.js"></script>
<title>Donated Book | UIU BookHUB</title>
</head>
<body>
<header class="site-navbar">
  <div class="container nav-inner">
    <a class="brand" href="index.php"><img src="assets/logo.png" alt="UIU BookHUB"></a>
    <button class="menu-toggle" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    <nav class="nav-links"><a href="index.php">Home</a><a class="active" href="marketplace.php">Marketplace</a><a href="index.php#how-it-works">How It Works</a></nav>
    <div class="nav-actions"><a href="login.php" class="btn btn-outline login-link">Login</a></div>
  </div>
</header>
<section class="section"><div class="container">
  <div class="breadcrumb" style="margin-bottom:22px;"><a href="marketplace.php" style="color:#FF6B00;">Marketplace</a> / <span id="donation-detail-breadcrumb">Donated Book</span></div>
  <div id="donation-details"><p>Loading donation...</p></div>
</div></section>
<div class="toast"></div>
<script src="js/pages.js"></script>
</body>
</html>
