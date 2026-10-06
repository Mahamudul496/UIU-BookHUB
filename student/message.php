<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css"><link rel="stylesheet" href="../css/dashboard.css">
<script defer src="../js/api.js"></script>
<script defer src="../js/main.js"></script>
<title>Messages | UIU BookHUB</title></head><body>
<div class="sidebar-layout">
<aside class="dashboard-sidebar">
  <div class="side-brand"><a href="../index.php"><img src="../assets/logo.png" alt="UIU BookHUB"></a></div>
  <nav class="side-nav"><div class="side-label">Student Area</div><a class="" href="dashboard.php"><i class="fa-solid fa-chart-pie"></i>Dashboard</a><a class="" href="marketplace.php"><i class="fa-solid fa-store"></i>Marketplace</a><a class="" href="add-listing.php"><i class="fa-solid fa-plus-circle"></i>Add Listing</a><a class="" href="my-listings.php"><i class="fa-solid fa-book"></i>My Listings</a><a class="" href="wishlist.php"><i class="fa-solid fa-heart"></i>Wishlist</a><a class="active" href="messages.php"><i class="fa-solid fa-comments"></i>Messages</a><a class="" href="purchases.php"><i class="fa-solid fa-cart-shopping"></i>My Purchases</a><a class="" href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></nav>
  <div class="side-bottom"><a href="../index.php"><i class="fa-solid fa-house"></i> Public Website</a><a href="../index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></div>
</aside><main class="dashboard-main">
<header class="dashboard-topbar">
  <div class="topbar-title"><h1>Messages</h1><p>Chat with students about listings</p></div>
  <div class="topbar-right"><div class="notification"><i class="fa-regular fa-bell"></i><span class="dot"></span></div><div class="user-mini"><div class="avatar">MH</div><div><strong>Mahamudul Hasan</strong><span>UIU Student</span></div></div></div>
</header><div class="dashboard-content">
<div class="message-layout">
  <div class="conversations">
    <div class="conv-search"><input id="conversation-search" type="search" placeholder="Search messages..." aria-label="Search messages"></div>
    <div id="conversation-list"><p style="padding:18px;color:#777;">Loading conversations...</p></div>
  </div>
  <div class="chat-window">
    <div class="chat-head" id="chat-head"><div><strong>Select a conversation</strong><span>Your listing conversations appear here.</span></div></div>
    <div class="chat-body" id="chat-body"><p style="color:#777;">Choose a conversation or open Chat with Seller from a listing.</p></div>
    <form class="chat-input" id="chat-form" hidden><input id="chat-message" name="body" maxlength="2000" placeholder="Type a message..." autocomplete="off" required><button class="btn btn-primary btn-sm" type="submit">Send</button></form>
  </div>
</div>
</div></main></div><div class="toast"></div><script src="../js/pages.js"></script></body></html>