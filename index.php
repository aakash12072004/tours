<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Globe Trekker - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <img src="logo copy.png" alt="Logo" width="150">
        <ul>
            <div class="search-container">
                <form onsubmit="return searchRedirect()">
                    <input type="text" id="searchInput" placeholder="Search destination...">
                    <button type="submit">Search</button>
                </form>
            </div>
            
            <li><a href="#">Home</a></li>
            
            <li><a href="#destinations">Destinations</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <a class="book-btn" href="login.php">Book Now</a>
    </nav>

    
    <header class="hero">
        <video autoplay loop muted playsinline class="bg-video">
            <source src="bgvid.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="overlay"></div>
        <div class="hero-content">
            <h2>Explore the World with Us</h2>
            <p>Train, Bus, and Flight Booking Services</p>
            <a href="login.php" class="hero-btn">Get Started</a>
        </div>
    </header>

   

    
    <section id="destinations" class="section dark-bg">
        <h3>Popular Destinations</h3>
        <div class="grid">
            <div class="destination-box">
                <a href="udaipur.php">
                <img src="udaipur.jpg" alt="Udaipur"></a>
                <h4>Udaipur, India</h4>
                <p>The "City of Lakes" with stunning beauty and rich heritage.</p>
                
            </div>
            <div class="destination-box">
                <a href="nandihill.php">
                <img src="nandihill.png" alt="Nandi Hills"></a>
                <h4>Nandi Hills, India</h4>
                <p>A serene getaway near Bangalore, ideal for adventure lovers.</p>
            </div>
            <div class="destination-box">
                <a href="goa.php">
                <img src="goa.jpg" alt="Goa"></a>
                <h4>Goa, India</h4>
                <p>Vibrant beaches, delicious food, and an electrifying nightlife.</p>
            </div>

            <div class="destination-box">
                <a href="saputara.php">
                <img src="saputara.jpg" alt="Saputara"></a>
                <h4>Saputara, India</h4>
                <p>A refreshing spot to enjoy nature and the sound of water in Saputara.</p>
            </div>

            <div class="destination-box">
                <a href="kashmir.php">
                <img src="kashmir.jpg" alt="Kashmir"></a>
                <h4>Kashmir, India</h4>
                <p>The perfect escape into nature, where mountains kiss the sky.</p>
                
            </div>

            <div class="destination-box">
                <a href="kedarkantha.php">
                <img src="kedarkantha.jpg" alt="Kedarkantha"></a>
                <h4>Kedarkantha, India</h4>
                <p>Kedarkantha is a stunning trekking destination known for its snow-covered peaks and scenic beauty.</p>
            </div>
        </div>
    </section>

   
  <section class="about">
    <div class="about-content">
      <h1>About Us</h1>
      <p>At <strong>GlobeTrekker</strong>, we’re passionate about creating unforgettable journeys. Whether you're looking to explore the snowy valleys of Kashmir or the deserts of Rajasthan, we’ve got something exciting for every traveler.</p>
      
      <p>Our experienced team is dedicated to organizing seamless trips that blend adventure, comfort, and culture. From train bookings and accommodations to guided tours, we handle it all so you can just focus on enjoying the ride.</p>

      <p>Founded in 2020, we’ve proudly helped over 10,000 travelers make beautiful memories across India and beyond.</p>
    </div>


  </section>

    
    <section id="contact" class="section dark-bg">
        <h3>Contact Us</h3>
        <p>Have questions? Reach out to us!</p>
        <a class="contact-btn" href="contact.html">Get in Touch</a>
   
    </section>


   
    <footer class="footer">
        <p>&copy; 2025 Globe Trekker. All rights reserved.</p>
    </footer>
    <script>
        function searchRedirect() {
            const input = document.getElementById('searchInput').value.toLowerCase().trim();
    
            const pages = {
                "udaipur": "udaipur.php",
                "nandi hills": "nandihill.php",
                "goa": "goa.php",
                "saputara": "saputara.php",
                "kashmir": "kashmir.php",
                "kedarkantha": "kedarkantha.php"
            };
    
            if (pages[input]) {
                window.location.href = pages[input];
            } else {
                alert("Destination not found. Try 'Udaipur', 'Goa', etc.");
            }
            return false; // Prevent form default submit
        }
    </script>
    
</body>
</html>
