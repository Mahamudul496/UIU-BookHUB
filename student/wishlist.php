<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="../css/dashboard.css">
<script defer src="../js/api.js"></script>
<script defer src="../js/main.js"></script>
<title>Wishlist | UIU BookHUB</title></head><body>
<div class="sidebar-layout">
<aside class="dashboard-sidebar">
  <div class="side-brand"><a href="../index.php"><img src="../assets/logo.png" alt="UIU BookHUB"></a></div>
  <nav class="side-nav"><div class="side-label">Student Area</div><a class="" href="dashboard.php"><i class="fa-solid fa-chart-pie"></i>Dashboard</a><a class="" href="marketplace.php"><i class="fa-solid fa-store"></i>Marketplace</a><a class="" href="add-listing.php"><i class="fa-solid fa-plus-circle"></i>Add Listing</a><a class="" href="my-listings.php"><i class="fa-solid fa-book"></i>My Listings</a><a class="active" href="wishlist.php"><i class="fa-solid fa-heart"></i>Wishlist</a><a class="" href="messages.php"><i class="fa-solid fa-comments"></i>Messages</a><a class="" href="purchases.php"><i class="fa-solid fa-cart-shopping"></i>My Purchases</a><a class="" href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></nav>
  <div class="side-bottom"><a href="../index.php"><i class="fa-solid fa-house"></i> Public Website</a><a href="../index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></div>
</aside><main class="dashboard-main">
<header class="dashboard-topbar">
  <div class="topbar-title"><h1>Wishlist</h1><p>Books you want to buy later</p></div>
  <div class="topbar-right"><div class="notification"><i class="fa-regular fa-bell"></i><span class="dot"></span></div><div class="user-mini"><div class="avatar">MH</div><div><strong>Mahamudul Hasan</strong><span>UIU Student</span></div></div></div>
</header><div class="dashboard-content">
<div class="section-header" style="margin-bottom:18px;"><div><h2 style="font-size:22px;">Saved Items</h2><p>Your wishlist items.</p></div></div>
<div class="grid grid-3" id="wishlist-grid"><p>Loading wishlist...</p></div>
</div></main></div><div class="toast"></div><script src="../js/pages.js"></script></body></html>