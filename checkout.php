<?php
require_once 'config.php';

if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}

$flight_id = (int)$_POST['flight_id'];
$passengers = (int)$_POST['passengers'];
$total_price = (float)$_POST['total_price'];

// Save passenger data in session to process after payment
$_SESSION['pending_booking'] = [
    'flight_id' => $flight_id,
    'passengers' => $passengers,
    'total_price' => $total_price,
    'pax_data' => []
];

for($i=1; $i<=$passengers; $i++) {
    $_SESSION['pending_booking']['pax_data'][] = [
        'name' => $_POST["name_$i"],
        'dob' => $_POST["dob_$i"],
        'passport' => $_POST["passport_$i"],
        'seat' => $_POST["seat_$i"]
    ];
}

include 'header.php';
?>

<div class="container py-5 d-flex justify-content-center">
    <div class="glass-panel p-5" style="max-width: 600px; width: 100%;">
        <h2 class="text-center mb-4" style="color: #00c6ff; text-transform: uppercase;"><i class="bi bi-credit-card"></i> Secure Checkout</h2>
        
        <div class="alert text-center mb-4" style="background: rgba(255,215,0,0.1); border: 1px dashed #00c6ff; color: white;">
            Total Amount Due: <strong class="fs-4 text-info">$<?php echo number_format($total_price, 2); ?></strong>
        </div>

        <form action="process_booking.php" method="POST">
            <h5 class="mb-3 text-light">Payment Details</h5>
            <div class="mb-3">
                <label class="form-label text-muted">Name on Card</label>
                <input type="text" class="form-control" required placeholder="JANE DOE">
            </div>
            <div class="mb-3">
                <label class="form-label text-muted">Card Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark text-white border-secondary"><i class="bi bi-credit-card-fill text-info"></i></span>
                    <input type="text" class="form-control" required placeholder="XXXX-XXXX-XXXX-XXXX" maxlength="19">
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label text-muted">Expiry</label>
                    <input type="text" class="form-control" required placeholder="MM/YY">
                </div>
                <div class="col-6">
                    <label class="form-label text-muted">CVV</label>
                    <input type="password" class="form-control" required placeholder="123" maxlength="3">
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-custom fs-5">Pay & Confirm Booking</button>
                <a href="javascript:history.back()" class="btn btn-outline-light mt-2">Cancel</a>
            </div>
            
            <div class="text-center mt-4">
                <small class="text-muted"><i class="bi bi-lock-fill text-success"></i> 256-bit Secure Mock Payment. No real charges.</small>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
