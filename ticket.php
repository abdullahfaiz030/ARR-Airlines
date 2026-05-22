<?php
require_once 'config.php';

if (!isLoggedIn() || !isset($_GET['pnr'])) {
    header("Location: index.php");
    exit;
}

$pnr = $_GET['pnr'];

// Fetch booking details
$stmt = $conn->prepare("SELECT b.booking_date, b.total_price, b.status, f.flight_number, f.departure_time, f.arrival_time, 
                        o.city as orig_city, o.code as orig_code, 
                        d.city as dest_city, d.code as dest_code 
                        FROM bookings b 
                        JOIN flights f ON b.flight_id = f.id 
                        JOIN airports o ON f.origin_id = o.id 
                        JOIN airports d ON f.dest_id = d.id 
                        WHERE b.pnr = ? AND (b.user_id = ? OR 'admin' = ?)");
$admin_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'user';
$stmt->bind_param("sis", $pnr, $_SESSION['user_id'], $admin_role);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$booking) {
    die("Invalid PNR or unauthorized access.");
}

// Fetch passengers
$passengers = [];
$stmt = $conn->prepare("SELECT name, seat_number FROM passengers WHERE booking_id = (SELECT id FROM bookings WHERE pnr = ?)");
$stmt->bind_param("s", $pnr);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $passengers[] = $row;
}
$stmt->close();

include 'header.php';
?>

<div class="container py-5 d-flex justify-content-center">
    <div class="glass-panel p-0" style="max-width: 800px; width: 100%; border: 2px solid #00c6ff; overflow: hidden;">
        
        <!-- Ticket Header -->
        <div class="p-4" style="background: linear-gradient(90deg, #111, #333);">
            <div class="d-flex justify-content-between align-items-center border-bottom border-info pb-3 mb-3">
                <h2 style="color: #00c6ff; margin:0;"><i class="bi bi-airplane-engines-fill"></i> ARR AIRLINES</h2>
                <div class="text-end">
                    <span class="text-muted d-block text-uppercase" style="font-size: 0.8rem;">Boarding Pass / E-Ticket</span>
                    <h3 class="text-white mb-0">PNR: <span class="text-info"><?php echo htmlspecialchars($pnr); ?></span></h3>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-md-5 text-center text-md-start">
                    <h1 class="display-4 text-white mb-0"><?php echo $booking['orig_code']; ?></h1>
                    <p class="text-muted fs-5 mb-0"><?php echo $booking['orig_city']; ?></p>
                    <div class="text-info fw-bold fs-5 mt-2"><?php echo date('d M Y, H:i', strtotime($booking['departure_time'])); ?></div>
                </div>
                <div class="col-md-2 text-center py-3">
                    <i class="bi bi-arrow-right text-info display-4 d-none d-md-block"></i>
                    <i class="bi bi-arrow-down text-info display-4 d-md-none"></i>
                    <br>
                    <span class="badge bg-info text-dark mt-2 px-3 py-1"><?php echo $booking['flight_number']; ?></span>
                </div>
                <div class="col-md-5 text-center text-md-end">
                    <h1 class="display-4 text-white mb-0"><?php echo $booking['dest_code']; ?></h1>
                    <p class="text-muted fs-5 mb-0"><?php echo $booking['dest_city']; ?></p>
                    <div class="text-info fw-bold fs-5 mt-2"><?php echo date('d M Y, H:i', strtotime($booking['arrival_time'])); ?></div>
                </div>
            </div>
        </div>

        <!-- Passengers Details -->
        <div class="p-4 bg-dark">
            <h5 class="text-info border-bottom border-secondary pb-2 mb-3">Passenger Information</h5>
            <div class="row g-3">
                <?php foreach($passengers as $p): ?>
                <div class="col-md-6">
                    <div class="border border-secondary rounded p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block text-uppercase">Passenger Name</small>
                            <span class="fs-5 text-white fw-bold"><?php echo htmlspecialchars($p['name']); ?></span>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block text-uppercase">Seat</small>
                            <span class="fs-4 text-info fw-bold"><?php echo htmlspecialchars($p['seat_number']); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Footer actions -->
        <div class="p-3 text-center" style="background: rgba(0,0,0,0.5);">
            <button class="btn btn-info px-4 py-2 me-2" onclick="window.print()"><i class="bi bi-printer-fill"></i> Print Ticket</button>
            <a href="dashboard.php" class="btn btn-outline-light px-4 py-2">Go to Dashboard</a>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .glass-panel, .glass-panel * { visibility: visible; }
        .glass-panel { position: absolute; left: 0; top: 0; width: 100%; border: none !important; }
        .btn { display: none !important; }
        header, footer { display: none !important; }
    }
</style>

<?php include 'footer.php'; ?>
