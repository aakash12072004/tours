function updateTotal() {
    let guestCount = parseInt(document.getElementById('guests').value); // Get number of guests
    let pricePerGuest =14000; // Fixed price per person
    let serviceChargePerGuest = 200; // Service charge per person

    let totalCost = (guestCount * pricePerGuest) + (guestCount * serviceChargePerGuest); // Calculate total
    let serviceChargeTotal = guestCount * serviceChargePerGuest; // Total service charge

    document.getElementById('guestCount').innerText = guestCount; // Update guest count
    document.getElementById('totalCost').innerText = totalCost; // Update total price
}
