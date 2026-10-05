<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="css/style.css?v=donor-panel">
<link rel="stylesheet" href="css/dashboard.css">
<script defer src="js/api.js"></script>
<script defer src="js/main.js"></script>
<title>Donate a Book | UIU BookHUB</title>
</head>
<body>
<header class="site-navbar">
  <div class="container nav-inner">
    <a class="brand" href="index.php"><img src="assets/logo.png" alt="UIU BookHUB"></a>
    <button class="menu-toggle" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
    <nav class="nav-links"><a href="index.php">Home</a><a href="marketplace.php">Marketplace</a><a href="index.php#how-it-works">How It Works</a></nav>
    <div class="nav-actions"><a href="login.php" class="btn btn-outline login-link">Login</a></div>
  </div>
</header>
<section class="page-head"><div class="container"><div class="breadcrumb"><a href="index.php">Home</a> / Donate a Book</div><h1>Donate a Book</h1><p>Share a book or course material with another UIU student. You do not need to log in.</p></div></section>
<section class="section" style="padding-top:38px;"><div class="container">
  <div class="listing-page">
    <div class="notice"><i class="fa-solid fa-heart"></i> Donations are free. An admin reviews each submission before it appears in the marketplace.</div>
    <form id="donation-form" enctype="multipart/form-data">
      <section class="donor-identity-panel" aria-labelledby="donor-identity-heading">
        <div class="donor-identity-icon"><i class="fa-solid fa-hand-holding-heart" aria-hidden="true"></i></div>
        <div class="donor-identity-content">
          <span class="donor-identity-kicker">A little recognition goes a long way</span>
          <h2 id="donor-identity-heading">Who is sharing this book?</h2>
          <p>Your name or UIU Student ID helps the community appreciate your generosity.</p>
          <label for="donor-identity">Your name or Student ID <span>(optional)</span></label>
          <input class="form-control" id="donor-identity" name="donor_identity" maxlength="120" placeholder="e.g. Mahamudul Hasan or 011231234" autocomplete="name">
          <small>Shown publicly on the donated book. Leave blank to appear as Anonymous.</small>
        </div>
      </section>
      <div class="form-panel"><h2>Book Information</h2><p>Add the book details so students can find it.</p>
        <div class="form-grid">
          <div class="form-group"><label for="donation-title">Book / Note Title *</label><input class="form-control" id="donation-title" name="title" maxlength="180" placeholder="e.g. Database System Concepts" required></div>
          <div class="form-group"><label for="donation-course">Course Code *</label><input class="form-control" id="donation-course" name="course_code" maxlength="30" placeholder="e.g. CSE 311" required></div>
          <div class="form-group"><label for="donation-department">Department *</label><select class="form-control" id="donation-department" name="department" required><option value="CSE">CSE</option><option value="EEE">EEE</option><option value="BBA">BBA</option><option value="Economics">Economics</option><option value="English">English</option><option value="GED">GED</option></select></div>
          <div class="form-group"><label for="donation-subject">Subject</label><input class="form-control" id="donation-subject" name="subject" maxlength="120" placeholder="e.g. Database Management"></div>
          <div class="form-group"><label for="donation-type">Type</label><select class="form-control" id="donation-type" name="item_type"><option>Textbook</option><option>Lecture Notes</option><option>Lab Manual</option><option>Other Notes</option></select></div>
          <div class="form-group"><label for="donation-condition">Condition *</label><select class="form-control" id="donation-condition" name="condition_status" required><option>Like New</option><option>Good</option><option>Used</option></select></div>
          <div class="form-group"><label for="donation-edition">Edition / Author</label><input class="form-control" id="donation-edition" name="edition_author" maxlength="180" placeholder="Optional"></div>
          <div class="form-group form-full"><label for="donation-description">Description</label><textarea class="form-control" id="donation-description" name="description" placeholder="Describe the condition, highlighting, missing pages, or how a student can collect it."></textarea></div>
          <div class="form-group" hidden aria-hidden="true"><label for="website">Website</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
        </div>
      </div>
      <div class="form-panel"><h2>Book Photo</h2><p>A clear photo helps students identify the book. Photo is optional.</p>
        <div class="upload-box"><i class="fa-solid fa-cloud-arrow-up"></i><p id="donation-photo-label">Choose a photo (JPG/PNG/WEBP, max 2 MB)</p><input type="file" id="donation-photo-input" name="image" accept="image/jpeg,image/png,image/webp" hidden><button type="button" id="donation-photo-btn" class="btn btn-outline btn-sm">Choose Photo</button></div>
      </div>
      <div class="form-actions"><a href="index.php" class="btn btn-outline">Cancel</a><button class="btn btn-primary" type="submit">Submit Donation</button></div>
      <p id="donation-result" role="status" aria-live="polite"></p>
    </form>
  </div>
</div></section>
<div class="toast"></div>
<script src="js/pages.js"></script>
</body>
</html>
