
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/dashboard.css">
<script defer src="../js/api.js"></script>
<script defer src="../js/main.js"></script>
<title>Marketplace | UIU BookHUB</title>
</head>
<body>
<div class="sidebar-layout">
<aside class="dashboard-sidebar">
  <div class="side-brand"><a href="../index.php"><img src="../assets/logo.png" alt="UIU BookHUB"></a></div>
  <nav class="side-nav">
    <div class="side-label">Student Area</div>
    <a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i>Dashboard</a>
    <a class="active" href="marketplace.php"><i class="fa-solid fa-store"></i>Marketplace</a>
    <a href="add-listing.php"><i class="fa-solid fa-plus-circle"></i>Add Listing</a>
    <a href="my-listings.php"><i class="fa-solid fa-book"></i>My Listings</a>
    <a href="wishlist.php"><i class="fa-solid fa-heart"></i>Wishlist</a>
    <a href="messages.php"><i class="fa-solid fa-comments"></i>Messages</a>
    <a href="purchases.php"><i class="fa-solid fa-cart-shopping"></i>My Purchases</a>
    <a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a>
  </nav>
  <div class="side-bottom">
    <a href="../index.php"><i class="fa-solid fa-house"></i>Public Website</a>
    <a href="../index.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
  </div>
</aside>

<main class="dashboard-main">
<header class="dashboard-topbar">
  <div class="topbar-title"><h1>Marketplace</h1><p>Browse textbooks, notes and course materials from UIU students.</p></div>
  <div class="topbar-right"><div class="notification"><i class="fa-regular fa-bell"></i><span class="dot"></span></div><div class="user-mini"><div class="avatar">MH</div><div><strong>Mahamudul Hasan</strong><span>UIU Student</span></div></div></div>
</header>

<div class="dashboard-content student-market-content">
  <div class="student-market-toolbar">
    <div class="student-market-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search by title, course code or subject..."></div>
    <select class="student-market-sort"><option>Newest First</option><option>Price: Low to High</option><option>Price: High to Low</option></select>
  </div>

  <div class="student-market-layout">
    <aside class="student-filter panel">
      <div class="panel-head"><div><h2>Filter Listings</h2><p>Find the right course material.</p></div></div>
      <div class="filter-group"><label>Department</label><select><option>All Departments</option><option>CSE</option><option>EEE</option><option>BBA</option><option>Economics</option><option>English</option><option>GED</option></select></div>
      <div class="filter-group"><label>Course Code</label><input type="text" placeholder="e.g. CSE 311"></div>
      <div class="filter-group"><label>Condition</label>
        <label class="check-line"><input type="checkbox"> Like New</label>
        <label class="check-line"><input type="checkbox"> Good</label>
        <label class="check-line"><input type="checkbox"> Used</label>
      </div>
      <div class="filter-group"><label>Type</label>
        <label class="check-line"><input type="checkbox"> Textbook</label>
        <label class="check-line"><input type="checkbox"> Notes</label>
        <label class="check-line"><input type="checkbox"> Manual</label>
      </div>
      <button class="btn btn-primary filter-btn">Apply Filters</button>
    </aside>

    <section class="student-products-area">
      <div class="student-results-head"><div><strong>Available Listings</strong><span id="market-count">Loading…</span></div><div style="display:flex;gap:8px;flex-wrap:wrap;"><a href="../donation.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-heart"></i> Donate a Book</a><a href="add-listing.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Sell a Book</a></div></div>
      <div class="student-product-grid" id="market-grid"></div>
      
    </section>
  </div>
</div>
</main>
</div>
<div class="toast"></div><script src="../js/pages.js"></script>
</body>
</html>
