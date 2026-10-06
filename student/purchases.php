<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="../css/dashboard.css">
<script defer src="../js/api.js"></script>
<script defer src="../js/main.js"></script>
<title>My Purchases | UIU BookHUB</title></head><body>
<div class="sidebar-layout">
<aside class="dashboard-sidebar">
  <div class="side-brand"><a href="../index.php"><img src="../assets/logo.png" alt="UIU BookHUB"></a></div>
  <nav class="side-nav"><div class="side-label">Student Area</div><a class="" href="dashboard.php"><i class="fa-solid fa-chart-pie"></i>Dashboard</a><a class="" href="marketplace.php"><i class="fa-solid fa-store"></i>Marketplace</a><a class="" href="add-listing.php"><i class="fa-solid fa-plus-circle"></i>Add Listing</a><a class="" href="my-listings.php"><i class="fa-solid fa-book"></i>My Listings</a><a class="" href="wishlist.php"><i class="fa-solid fa-heart"></i>Wishlist</a><a class="" href="messages.php"><i class="fa-solid fa-comments"></i>Messages</a><a class="active" href="purchases.php"><i class="fa-solid fa-cart-shopping"></i>My Purchases</a><a class="" href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></nav>
  <div class="side-bottom"><a href="../index.php"><i class="fa-solid fa-house"></i> Public Website</a><a href="../index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></div>
</aside><main class="dashboard-main">
<header class="dashboard-topbar">
  <div class="topbar-title"><h1>My Purchases</h1><p>Your buying history and requests</p></div>
  <div class="topbar-right"><div class="notification"><i class="fa-regular fa-bell"></i><span class="dot"></span></div><div class="user-mini"><div class="avatar">MH</div><div><strong>Mahamudul Hasan</strong><span>UIU Student</span></div></div></div>
</header><div class="dashboard-content">
<div class="panel table-panel"><div class="panel-head"><div><h2>My Purchases</h2><p>Items you requested or purchased.</p></div></div>
<table class="dashboard-table"><thead><tr><th>Item</th><th>Seller</th><th>Price</th><th>Request Status</th><th>Action</th></tr></thead><tbody id="purchases-body"><tr><td colspan="5">Loading...</td></tr></tbody></table></div>
<p style="font-size:12px;color:#777;margin-top:14px;">A buy request is not a payment. Arrange handover and payment directly with the seller after they accept.</p>
<div class="panel" id="review-modal" hidden style="max-width:560px;margin-top:22px;">
  <div class="panel-head"><div><h2>Review Seller</h2><p id="review-item-title">Share your experience with this seller.</p></div></div>
  <form id="review-form">
    <input type="hidden" name="request_id" id="review-request-id">
    <div class="form-group"><label for="review-rating">Rating</label><select class="form-control" id="review-rating" name="rating" required><option value="">Choose a rating</option><option value="5">★★★★★ — Excellent</option><option value="4">★★★★ — Good</option><option value="3">★★★ — Okay</option><option value="2">★★ — Poor</option><option value="1">★ — Very poor</option></select></div>
    <div class="form-group"><label for="review-comment">Comment (optional)</label><textarea class="form-control" id="review-comment" name="comment" maxlength="1000" rows="3" placeholder="How was your experience?"></textarea></div>
    <div class="form-actions"><button type="button" class="btn btn-outline" id="review-cancel">Cancel</button><button type="submit" class="btn btn-primary">Submit Review</button></div>
  </form>
</div>
</div></main></div><div class="toast"></div><script src="../js/pages.js"></script></body></html>