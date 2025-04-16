const prices = { Bus: 11000, Train: 13000, Flight: 18000 };
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
