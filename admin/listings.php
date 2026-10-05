<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="../css/dashboard.css">
<script defer src="../js/api.js"></script>
<script defer src="../js/main.js"></script>
<title>Listing Management | UIU BookHUB Admin</title></head><body><div class="sidebar-layout">
<aside class="dashboard-sidebar admin-accent">
  <div class="side-brand"><a href="../index.php"><img src="../assets/logo.png" alt="UIU BookHUB"></a></div>
  <nav class="side-nav"><div class="side-label">Admin Panel</div><a class="" href="dashboard.php"><i class="fa-solid fa-chart-pie"></i>Dashboard</a><a class="active" href="listings.php"><i class="fa-solid fa-clipboard-check"></i>Listing Management</a></nav>
  <div class="side-bottom"><a href="../index.php"><i class="fa-solid fa-house"></i> Public Website</a><a href="../index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></div>
</aside><main class="dashboard-main"><header class="dashboard-topbar"><div class="topbar-title"><h1>Listing Management</h1><p>Approve, reject and review student listings</p></div><div class="topbar-right"><div class="notification"><i class="fa-regular fa-bell"></i><span class="dot"></span></div><div class="user-mini"><div class="avatar">AD</div><div><strong>Admin</strong><span>UIU BookHUB</span></div></div></div></header><div class="dashboard-content">
<div class="notice"><i class="fa-solid fa-shield-halved"></i> Review book listings and public donation submissions before they appear in the marketplace.</div>
<div class="panel">
  <div class="panel-head"><div><h2>Pending Submissions</h2><p>Approve or reject student listings and donations.</p></div><span class="status pending" id="pending-count">… Pending</span></div>
  <div id="pending-list"></div></div>
<div class="panel table-panel"><div class="panel-head"><div><h2>All Listings</h2><p>Student listings overview.</p></div></div>
<table class="dashboard-table"><thead><tr><th>Item</th><th>Seller</th><th>Course</th><th>Status</th><th>Reject Reason</th></tr></thead><tbody id="all-listings-body"></tbody></table></div>
<div class="panel table-panel"><div class="panel-head"><div><h2>Book Donations</h2><p>Public donation submissions and their moderation status.</p></div></div>
<table class="dashboard-table"><thead><tr><th>Book</th><th>Donor</th><th>Course</th><th>Status</th><th>Reject Reason</th></tr></thead><tbody id="all-donations-body"></tbody></table></div>
</div></main></div><div class="toast"></div><script src="../js/pages.js"></script></body></html>