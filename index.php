<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");
$today = date("Y-m-d");
$stmt = $conn->prepare("SELECT * FROM offers 
                        WHERE status='Active'
                        AND start_date <= ?
                        AND end_date >= ?
                        ORDER BY id DESC
                        LIMIT 1");
$stmt->bind_param("ss", $today, $today);
$stmt->execute();
$result = $stmt->get_result();
$offer = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restaurant Website</title>
<link rel="stylesheet" href="style.css?v=3">
<script src="https://kit.fontawesome.com/a4939d816a.js" crossorigin="anonymous"></script>
</head>
<body>

<!-- Navbar -->
<nav>
  <div class="navigation container">
    <div class="logo_container">
      <img src="logo.png" alt="logo"/>
    </div>

    <ul class="nav_links">
      <li><a href="menu.html" target="_blank">Our Menu</a></li>
      <li><a href="Tracking.php" target="_blank">Track Order</a></li>
      <li><a href="Contact.html" target="_blank">Contact Us</a></li>
    </ul>

    <div class="order_btn">
      <a href="order_form.php" target="_blank">Order Now</a>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<div class="container">
  <div class="hero">
    <div class="hero_image">
      <img src="hero_image.png" alt="hero image">
    </div>

    <div class="hero_content">
<?php if($offer): ?>
    <div class="tag">
        <?php echo $offer['offer_text']; ?>
    </div>
     <div id="countdown" style="margin-top:10px;color:#ff5722;font-weight:bold;"></div>
<script>

  window.onload = function () {
    window.scrollTo(0, 0);
  };


const endDate = new Date("<?php echo $offer['end_date']; ?>".replace(" ", "T")).getTime();

function updateCountdown() {

    const now = new Date().getTime();
    const distance = endDate - now;

    if (distance <= 0) {
        document.getElementById("countdown").innerHTML = "Offer Expired";
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("countdown").innerHTML =
        "Offer ends in: " +
        days + "d " +
        hours + "h " +
        minutes + "m " +
        seconds + "s";
}

updateCountdown();
setInterval(updateCountdown, 1000);
</script>
      <?php endif; ?>
<h1 class="hero-heading">
  Enjoy Your Delicious Food
</h1>

<p class="hero-tagline">
  Fresh, healthy meals delivered fast — crafted with love and trusted by thousands.
</p>

<a href="#menu" class="explore_btn">
  Explore Now →
</a>

<div class="trust-signals">
  <div class="trust-item">⭐ Rated 4.8★ on Google</div>
  <div class="trust-item">👨‍👩‍👧‍👦 5000+ happy customers served</div>
  <div class="trust-item">🚚 Fast delivery, guaranteed freshness</div>
</div>
    </div>
  </div>

  <!-- Features -->
  <section class="features">
    <div class="feature">
      <img src="discount.png" alt="">
      <div class="feature_content">
        <h3>Discount Voucher</h3>
        Save big with our special deals.
      </div>
    </div>

    <div class="feature">
      <img src="fresh.png" alt="">
      <div class="feature_content">
        <h3>Fresh Healthy Food</h3>
        Always fresh and made with love.
      </div>
    </div>

    <div class="feature">
      <img src="delivery.png" alt="">
      <div class="feature_content">
        <h3>Fast Home Delivery</h3>
        Quick and safe delivery at your door.
      </div>
    </div>
  </section>

  <div class="divider"></div>

  <!-- Menu Section -->
  <section id="menu" class="menu">
    <h2>Company Special Dishes</h2>

    <div class="grid">
      <img class="grid-image" src="grid_image1.png" alt="">
      <img class="grid-image" src="grid_image2.png" alt="">
      <img class="grid-image" src="grid_image3.png" alt="">
      <img class="grid-image" src="grid_image4.png" alt="">
      <img class="grid-image" src="grid_image5.png" alt="">
      <img class="grid-image" src="grid_image6.png" alt="">
      <img class="grid-image" src="grid_image7.png" alt="">
      <img class="grid-image" src="Butter_Naan.jpg" alt="">
    </div>
  </section>
</div>

<!-- Footer -->
<footer>
  <div class="footer_container container">
    <div class="footer_logo">
      <img src="logo.png" alt="">
    </div>

    <div class="link_lists">
      <h3>Main Links</h3>
      <ul>
        <li><a href="Tracking.php" target="_blank">Order Tracking</a></li>
        <li><a href="order_form.php" target="_blank">New Order</a></li>
        <li><a href="Contact.html" target="_blank">Contact Us</a></li>
        <li><a href="Feedback.php" target="_blank">Feedback</a></li>
      </ul>
    </div>

    <div class="link_lists">
      <h3>Support</h3>
      <ul>
        <li><a href="About.html" target="_blank">About Us</a></li>
        <li><a href="Privacy.html" target="_blank">Privacy Policy</a></li>
        <li><a href="Term.html" target="_blank">Terms & Conditions</a></li>
      </ul>
    </div>

    <div class="news_letter">
      <h3>Follow Us</h3>
      <div class="icon_container">
        <div class="icon"><a href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a></div>
        <div class="icon"><a href="https://x.com/twitt_login#"><i class="fa fa-twitter"></i></a></div>
        <div class="icon"><a href="https://www.instagram.com/accounts/login/?hl=en"><i class="fa fa-instagram"></i></a></div>
        <div class="icon"><a href="https://youtu.be/xPPLbEFbCAo?si=_bzBgWfea_F_c7nL"><i class="fa fa-youtube"></i></a></div>
      </div>
    </div>
  </div>

  <div style="text-align:center; padding:15px 0; font-size:12px; color:#aaa;">
    © 2026 Company |
    <a href="admin_login.php" target="_blank" style="color:#aaa;text-decoration:none;">
      Admin
    </a>
  </div>
</footer>

</body>
</html>