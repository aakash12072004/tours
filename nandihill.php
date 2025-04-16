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
    $trip = "Nandi Hills Tour";

    $email = $_POST["email"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $zip = $_POST["zip"];
    $cardname = $_POST["cardname"];
    $cardnumber = $_POST["cardnumber"];
    $expmonth = $_POST["expmonth"];
    $expyear = $_POST["expyear"];
    $cvv = $_POST["cvv"];

    if ($vehicle == "Bus") $price = 12000;
    elseif ($vehicle == "Train") $price = 13000;
    else $price = 15000;

    $service = 200;
    $total = ($price * $guests) + $service;

    $stmt1 = $conn->prepare("INSERT INTO bookings (name, phone, date, guests, total_cost, trip_name, vehicle) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt1->bind_param("sssisss", $name, $phone, $date, $guests, $total, $trip, $vehicle);
    $stmt1->execute();
    $stmt1->close();

    $stmt2 = $conn->prepare("INSERT INTO checkout_orders (fullname, email, address, city, state, zip, cardname, cardnumber, expmonth, expyear, cvv, total_amount)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt2->bind_param("sssssssssssd", $name, $email, $address, $city, $state, $zip, $cardname, $cardnumber, $expmonth, $expyear, $cvv, $total);
    $stmt2->execute();
    $stmt2->close();

    $bookingData = [
        "name" => $name,
        "phone" => $phone,
        "date" => $date,
        "guests" => $guests,
        "vehicle" => $vehicle,
        "total" => $total
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nandi Hills Trip Booking</title>
  <style>
    body { font-family: Arial; background: #f2f2f2; margin: 0; }
    .trip-image img { width: 100%; max-height: 300px; object-fit: cover; }
    .content-row { display: flex; justify-content: space-between; padding: 30px; flex-wrap: wrap; }
    .schedule, .booking-details {
      flex: 1; background: #fff; padding: 20px; margin: 10px;
      border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    .schedule-item { margin-bottom: 20px; border-left: 5px solid #cc0000; padding-left: 15px; }
    .booking-box input, .booking-box select {
      width: 100%; padding: 12px; margin: 10px 0;
      border-radius: 6px; border: 1px solid #ccc; font-size: 14px;
    }
    .book-btn {
      background: #0099cc; color: white; padding: 12px;
      border: none; border-radius: 5px; cursor: pointer;
      width: 100%; font-weight: bold;
    }
    .popup {
      display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6); justify-content: center;
      align-items: center; z-index: 100;
    }
    .popup-content {
      background: white; padding: 30px; border-radius: 12px; text-align: center;
    }
    .popup-content input {
      margin: 8px 0; padding: 10px; width: 100%;
      border: 1px solid #ccc; border-radius: 6px;
    }
    .success-box {
      background: #d4edda; padding: 15px; margin: 20px;
      border-radius: 10px; color: #155724;
    }
  </style>
</head>
<body>
<div class="trip-image"><img src="nandihill.png" alt="Nandi Hills Trip"></div>

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
        "DAY 1" => "Flight/train journey to Bengaluru, drive to Nandi Hills",
        "DAY 2" => "Explore Nandi Hills – Visit Tipu’s Drop, Nandi Fort, Sunrise Point",
        "DAY 3" => "Trekking & Nature Trail – Guided trek through nearby forest trails",
        "DAY 4" => "Local Exploration – Explore Bhoga Nandeeshwara Temple and Wine Yard",
        "DAY 5" => "Return to Ahmedabad – Drive back to Bengaluru & flight/train back"
      ];
      foreach ($schedule as $day => $desc) {
        echo "<div class='schedule-item'><span class='day'>$day</span><p>$desc</p></div>";
      }
    ?>
  </section>

  <div class="booking-details">
    <h2>Nandi Hills Tour</h2>
    <p><b>📍 Location:</b> Nandi Hills, Karnataka</p>
    <p><b>💰 Price:</b> ₹12000 per person</p>
    <p><b>🚗 Distance:</b> 1400 km approx</p>
    <p><b>👥 Group Size:</b> 8 people</p>

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

      <!-- Hidden fields for card and personal info -->
      <input type="hidden" name="email" id="email">
      <input type="hidden" name="address" id="address">
      <input type="hidden" name="city" id="city">
      <input type="hidden" name="state" id="state">
      <input type="hidden" name="zip" id="zip">
      <input type="hidden" name="cardname" id="cardname">
      <input type="hidden" name="cardnumber" id="cardnumber">
      <input type="hidden" name="expmonth" id="expmonth">
      <input type="hidden" name="expyear" id="expyear">
      <input type="hidden" name="cvv" id="cvv">

      <button type="button" class="book-btn" onclick="showPopup()">Book Now</button>
    </form>
  </div>
</div>

<div class="popup" id="qrPopup">
  <div class="popup-content">
    <h3>Enter Payment Details</h3>
    <form id="cardForm" onsubmit="processPayment(); return false;">
      <input type="email" id="popupEmail" placeholder="Email" required>
      <input type="text" id="popupAddress" placeholder="Address" required>
      <input type="text" id="popupCity" placeholder="City" required>
      <input type="text" id="popupState" placeholder="State" required>
      <input type="text" id="popupZip" placeholder="ZIP" required>
      <input type="text" placeholder="Name on Card" required>
      <input type="text" placeholder="Card Number" required>
      <input type="text" placeholder="Expiry Month" required>
      <input type="text" placeholder="Expiry Year" required>
      <input type="text" placeholder="CVV" required>
      <button class="book-btn">✅ Payment Done</button>
    </form>
  </div>
</div>

<script>
const prices = { Bus: 12000, Train: 13000, Flight: 15000 };
const guestInput = document.getElementById("guests");
const vehicleSelect = document.getElementById("vehicle");
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

function showPopup() {
  if (!vehicleSelect.value) {
    alert("Please select a vehicle type!");
    return;
  }
  document.getElementById("qrPopup").style.display = "flex";
}

function processPayment() {
  document.getElementById("email").value = document.getElementById("popupEmail").value;
  document.getElementById("address").value = document.getElementById("popupAddress").value;
  document.getElementById("city").value = document.getElementById("popupCity").value;
  document.getElementById("state").value = document.getElementById("popupState").value;
  document.getElementById("zip").value = document.getElementById("popupZip").value;

  const inputs = document.querySelectorAll("#cardForm input");
  document.getElementById("cardname").value = inputs[5].value;
  document.getElementById("cardnumber").value = inputs[6].value;
  document.getElementById("expmonth").value = inputs[7].value;
  document.getElementById("expyear").value = inputs[8].value;
  document.getElementById("cvv").value = inputs[9].value;

  document.getElementById("qrPopup").style.display = "none";
  document.getElementById("bookingForm").submit();
}
</script>
<?php endif; ?>
</body>
</html>
