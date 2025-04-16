<?php
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$bookingData = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["confirmed"]) && $_POST["confirmed"] === "yes") {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $date = $_POST["date"];
    $guests = $_POST["guests"];
    $vehicle = $_POST["vehicle"];
    $trip = "Kashmir Tour";

    if ($vehicle == "Bus") $price = 15000;
    elseif ($vehicle == "Train") $price = 17000;
    else $price = 20000;

    $service = 200;
    $total = ($price * $guests) + $service;

    $stmt = $conn->prepare("INSERT INTO bookings (name, phone, date, guests, total_cost, trip_name, vehicle) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $name, $phone, $date, $guests, $total, $trip, $vehicle);

    if ($stmt->execute()) {
        $bookingData = [
            "name" => $name,
            "phone" => $phone,
            "date" => $date,
            "guests" => $guests,
            "vehicle" => $vehicle,
            "total" => $total
        ];
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kashmir Trip Booking</title>
  <style>
    /* Include same CSS as previous trips for consistent design */
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      margin: 0;
    }
    .trip-image img {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
    }
    .content-row {
      display: flex;
      justify-content: space-between;
      padding: 30px;
      flex-wrap: wrap;
    }
    .schedule, .booking-details {
      flex: 1;
      background: #fff;
      padding: 20px;
      margin: 10px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }
    .schedule-item {
      margin-bottom: 20px;
      border-left: 5px solid #cc0000;
      padding-left: 15px;
    }
    .booking-box input, .booking-box select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }
    .book-btn {
      background: #0099cc;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
      font-weight: bold;
      transition: background 0.3s;
    }
    .popup {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 100;
    }
    .popup-content {
      background: white;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
    }
    .success-box {
      background: #d4edda;
      padding: 15px;
      margin: 20px;
      border-radius: 10px;
      color: #155724;
    }
  </style>
</head>
<body>
<div class="trip-image"><img src="kashmir.jpg" alt="Kashmir Trip"></div>

<?php if ($bookingData): ?>
<div class="success-box">
  <h3>✅ Booking Successful!</h3>
  <p><b>Name:</b> <?= $bookingData["name"] ?></p>
  <p><b>Phone:</b> <?= $bookingData["phone"] ?></p>
  <p><b>Date:</b> <?= $bookingData["date"] ?></p>
  <p><b>Guests:</b> <?= $bookingData["guests"] ?></p>
  <p><b>Vehicle:</b> <?= $bookingData["vehicle"] ?></p>
  <p><b>Total Cost:</b> ₹<?= $bookingData["total"] ?></p>
</div>
<?php else: ?>
<div class="content-row">
  <section class="schedule">
    <h2>Schedule</h2>
    <?php
      $schedule = [
        "DAY 1" => "Arrival in Srinagar – Boat ride on Dal Lake, explore Mughal Gardens",
        "DAY 2" => "Visit Gulmarg – Cable car ride, snow activities",
        "DAY 3" => "Sonamarg – Scenic beauty, trekking",
        "DAY 4" => "Pahalgam – Betaab Valley, Lidder River",
        "DAY 5" => "Return to Srinagar and Departure"
      ];
      foreach ($schedule as $day => $desc) {
        echo "<div class='schedule-item'><span class='day'>$day</span><p>$desc</p></div>";
      }
    ?>
  </section>

  <div class="booking-details">
    <h2>Kashmir Tour</h2>
    <p><b>📍 Location:</b> Kashmir, India</p>
    <p><b>💰 Price:</b> ₹15000 per person</p>
    <p><b>🚗 Distance:</b> 1800 km approx</p>
    <p><b>👥 Group Size:</b> 12 people</p>

    <form method="POST" class="booking-box" id="bookingForm">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="tel" name="phone" placeholder="Phone Number" required>
      <input type="date" name="date" required>
      <input type="number" name="guests" value="1" min="1" id="guests" required>

      <select name="vehicle" id="vehicle" required>
        <option value="" disabled selected>Select Vehicle</option>
        <option value="Bus">Bus 🚌</option>
        <option value="Train">Train 🚆</option>
        <option value="Flight">Flight ✈️</option>
      </select>

      <div class="cost-details">
        <p>₹0 x <span id="guestCount">1</span> person</p>
        <p>Service Charge: ₹200</p>
        <p><b>Total: ₹<span id="totalCost">200</span></b></p>
      </div>

      <input type="hidden" name="confirmed" value="yes">
      <button type="button" class="book-btn" onclick="showQR()">Book Now</button>
    </form>
  </div>
</div>

<div class="popup" id="qrPopup">
  <div class="popup-content">
    <h3>Scan to Pay</h3>
    <img src="payment_qr.jpeg" alt="QR Code">
    <p>Once paid, click below to confirm</p>
    <button onclick="submitForm()">✅ Payment Done</button>
  </div>
</div>

<script>
  const prices = { Bus: 15000, Train: 17000, Flight: 20000 };
  const vehicleSelect = document.getElementById("vehicle");
  const guestInput = document.getElementById("guests");
  const guestCountSpan = document.getElementById("guestCount");
  const totalCostSpan = document.getElementById("totalCost");

  function updateTotal() {
    const guests = parseInt(guestInput.value) || 1;
    const vehicle = vehicleSelect.value;
    const serviceCharge = 200;

    if (vehicle && prices[vehicle]) {
      const price = prices[vehicle];
      const total = price * guests + serviceCharge;

      guestCountSpan.innerText = guests;
      totalCostSpan.innerText = total;
      document.querySelector(".cost-details p").innerHTML = `₹${price} x <span id="guestCount">${guests}</span> person`;
    }
  }

  vehicleSelect.addEventListener("change", updateTotal);
  guestInput.addEventListener("input", updateTotal);

  function showQR() {
    if (!vehicleSelect.value) {
      alert("Please select a vehicle type!");
      return;
    }
    document.getElementById("qrPopup").style.display = "flex";
  }

  function submitForm() {
    document.getElementById("qrPopup").style.display = "none";
    document.getElementById("bookingForm").submit();
  }
</script>
<?php endif; ?>
</body>
</html>
