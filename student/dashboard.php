<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="../css/dashboard.css">
<script defer src="../js/api.js"></script>
<script defer src="../js/main.js"></script>
<title>Dashboard | UIU BookHUB</title></head><body>
<div class="sidebar-layout">
<aside class="dashboard-sidebar">
  <div class="side-brand"><a href="../index.php"><img src="../assets/logo.png" alt="UIU BookHUB"></a></div>
  <nav class="side-nav"><div class="side-label">Student Area</div><a class="active" href="dashboard.php"><i class="fa-solid fa-chart-pie"></i>Dashboard</a><a class="" href="marketplace.php"><i class="fa-solid fa-store"></i>Marketplace</a><a class="" href="add-listing.php"><i class="fa-solid fa-plus-circle"></i>Add Listing</a><a class="" href="my-listings.php"><i class="fa-solid fa-book"></i>My Listings</a><a class="" href="wishlist.php"><i class="fa-solid fa-heart"></i>Wishlist</a><a class="" href="messages.php"><i class="fa-solid fa-comments"></i>Messages</a><a class="" href="purchases.php"><i class="fa-solid fa-cart-shopping"></i>My Purchases</a><a class="" href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></nav>
  <div class="side-bottom"><a href="../index.php"><i class="fa-solid fa-house"></i> Public Website</a><a href="../index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></div>
</aside><main class="dashboard-main">
<header class="dashboard-topbar">
  <div class="topbar-title"><h1>Dashboard</h1><p>Your buying and selling overview</p></div>
  <div class="topbar-right"><div class="notification"><i class="fa-regular fa-bell"></i><span class="dot"></span></div><div class="user-mini"><div class="avatar">MH</div><div><strong>Mahamudul Hasan</strong><span>UIU Student</span></div></div></div>
</header><div class="dashboard-content">
<div class="stats-grid">
  <div class="stat-card"><div><p>Active Listings</p><h3 id="dashboard-active-listings">—</h3><small>Approved and available</small></div><div class="stat-icon"><i class="fa-solid fa-book"></i></div></div>
  <div class="stat-card"><div><p>Wishlist Items</p><h3 id="dashboard-wishlist-count">—</h3><small>Currently available</small></div><div class="stat-icon"><i class="fa-regular fa-heart"></i></div></div>
  <div class="stat-card"><div><p>Purchase Requests</p><h3 id="dashboard-purchases-count">—</h3><small>Your requests</small></div><div class="stat-icon"><i class="fa-solid fa-cart-shopping"></i></div></div>
  <div class="stat-card"><div><p>Items Sold</p><h3 id="dashboard-sold-count">—</h3><small>Listings marked sold</small></div><div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div></div>
</div>
<div class="notice"><i class="fa-solid fa-circle-info"></i> You can use this same account to buy and sell books. Your new listings will appear after admin approval.</div>
<div class="two-panel">
  <section class="panel table-panel"><div class="panel-head"><div><h2>My Recent Listings</h2><p>Your latest selling activity</p></div><a href="my-listings.php" class="btn btn-outline btn-sm">View All</a></div>
    <table class="dashboard-table"><thead><tr><th>Item</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody id="dashboard-recent-listings"><tr><td colspan="4">Loading…</td></tr></tbody></table>
  </section>
  <section class="panel"><div class="panel-head"><div><h2>Quick Actions</h2><p>Common student actions</p></div></div>
    <div class="quick-links">
      <a class="quick-link" href="add-listing.php"><i class="fa-solid fa-plus"></i><span>Add Book</span></a>
      <a class="quick-link" href="wishlist.php"><i class="fa-regular fa-heart"></i><span>Wishlist</span></a>
      <a class="quick-link" href="messages.php"><i class="fa-solid fa-comments"></i><span>Messages</span></a>
      <a class="quick-link" href="purchases.php"><i class="fa-solid fa-bag-shopping"></i><span>Purchases</span></a>
    </div>
  </section>
</div>
</div></main></div><div class="toast"></div><script src="../js/pages.js"></script></body></html>