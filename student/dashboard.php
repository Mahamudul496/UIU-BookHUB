<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="../css/dashboard.css">
<script defer src="../js/main.js"></script>
<title>Dashboard | UIU BookHUB</title></head><body>
<div class="sidebar-layout">
<aside class="dashboard-sidebar">
  <div class="side-brand"><a href="../index.html"><img src="../assets/logo.png" alt="UIU BookHUB"></a></div>
  <nav class="side-nav"><div class="side-label">Student Area</div><a class="active" href="dashboard.html"><i class="fa-solid fa-chart-pie"></i>Dashboard</a><a class="" href="marketplace.html"><i class="fa-solid fa-store"></i>Marketplace</a><a class="" href="add-listing.html"><i class="fa-solid fa-plus-circle"></i>Add Listing</a><a class="" href="my-listings.html"><i class="fa-solid fa-book"></i>My Listings</a><a class="" href="wishlist.html"><i class="fa-solid fa-heart"></i>Wishlist</a><a class="" href="messages.html"><i class="fa-solid fa-comments"></i>Messages</a><a class="" href="purchases.html"><i class="fa-solid fa-cart-shopping"></i>My Purchases</a><a class="" href="profile.html"><i class="fa-solid fa-user"></i>Profile</a></nav>
  <div class="side-bottom"><a href="../index.html"><i class="fa-solid fa-house"></i> Public Website</a><a href="../index.html"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></div>
</aside><main class="dashboard-main">
<header class="dashboard-topbar">
  <div class="topbar-title"><h1>Dashboard</h1><p>Your buying and selling overview</p></div>
  <div class="topbar-right"><div class="notification"><i class="fa-regular fa-bell"></i><span class="dot"></span></div><div class="user-mini"><div class="avatar">MH</div><div><strong>Mahamudul Hasan</strong><span>UIU Student</span></div></div></div>
</header><div class="dashboard-content">
<div class="stats-grid">
  <div class="stat-card"><div><p>Active Listings</p><h3>4</h3><small>+1 this week</small></div><div class="stat-icon"><i class="fa-solid fa-book"></i></div></div>
  <div class="stat-card"><div><p>Wishlist Items</p><h3>3</h3><small>2 available now</small></div><div class="stat-icon"><i class="fa-regular fa-heart"></i></div></div>
  <div class="stat-card"><div><p>Purchases</p><h3>6</h3><small>1 pending</small></div><div class="stat-icon"><i class="fa-solid fa-cart-shopping"></i></div></div>
  <div class="stat-card"><div><p>Items Sold</p><h3>8</h3><small>Good activity</small></div><div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div></div>
</div>
<div class="notice"><i class="fa-solid fa-circle-info"></i> You can use this same account to buy and sell books. Your new listings will appear after admin approval.</div>
<div class="two-panel">
  <section class="panel table-panel"><div class="panel-head"><div><h2>My Recent Listings</h2><p>Your latest selling activity</p></div><a href="my-listings.html" class="btn btn-outline btn-sm">View All</a></div>
    <table class="dashboard-table"><thead><tr><th>Item</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody>
      <tr><td><div class="book-cell"><img class="book-thumb" src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&q=80&w=120"><div><strong>Database System Concepts</strong><span>CSE 311</span></div></div></td><td>৳650</td><td><span class="status available">Available</span></td><td><a href="my-listings.html" class="btn btn-outline btn-sm">View</a></td></tr>
      <tr><td><div class="book-cell"><img class="book-thumb" src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&q=80&w=120"><div><strong>Computer Networks</strong><span>CSE 421</span></div></div></td><td>৳900</td><td><span class="status pending">Pending</span></td><td><a href="my-listings.html" class="btn btn-outline btn-sm">View</a></td></tr>
      <tr><td><div class="book-cell"><img class="book-thumb" src="https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&q=80&w=120"><div><strong>OOP</strong><span>CSE 213</span></div></div></td><td>৳500</td><td><span class="status sold">Sold</span></td><td><a href="my-listings.html" class="btn btn-outline btn-sm">View</a></td></tr>
    </tbody></table>
  </section>
  <section class="panel"><div class="panel-head"><div><h2>Quick Actions</h2><p>Common student actions</p></div></div>
    <div class="quick-links">
      <a class="quick-link" href="add-listing.html"><i class="fa-solid fa-plus"></i><span>Add Book</span></a>
      <a class="quick-link" href="wishlist.html"><i class="fa-regular fa-heart"></i><span>Wishlist</span></a>
      <a class="quick-link" href="messages.html"><i class="fa-solid fa-comments"></i><span>Messages</span></a>
      <a class="quick-link" href="purchases.html"><i class="fa-solid fa-bag-shopping"></i><span>Purchases</span></a>
    </div>
  </section>
</div>
</div></main></div><div class="toast"></div></body></html>