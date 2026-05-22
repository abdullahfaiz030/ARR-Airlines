<?php
require_once 'config.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's bookings
$bookings = [];
$stmt = $conn->prepare("SELECT b.pnr, b.booking_date, b.total_price, b.status, 
                        f.flight_number, f.departure_time, f.arrival_time,
                        o.city as orig_city, d.city as dest_city
                        FROM bookings b 
                        JOIN flights f ON b.flight_id = f.id 
                        JOIN airports o ON f.origin_id = o.id 
                        JOIN airports d ON f.dest_id = d.id 
                        WHERE b.user_id = ? 
                        ORDER BY b.booking_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $bookings[] = $row;
}
$stmt->close();

include 'header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #00c6ff; text-transform: uppercase;"><i class="bi bi-person-circle"></i> My Bookings</h2>
    </div>

    <?php if(empty($bookings)): ?>
        <div class="glass-panel p-5 text-center">
            <i class="bi bi-calendar-x display-1 mb-3 text-muted"></i>
            <h3 class="text-white">No bookings yet</h3>
            <p class="text-muted">You haven't booked any flights with us. Time to plan your next luxury trip!</p>
            <a href="index.php" class="btn btn-custom mt-3">Search Flights</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($bookings as $b): 
                $badge = 'bg-secondary';
                if($b['status'] == 'Confirmed') $badge = 'bg-success';
                if($b['status'] == 'Cancelled') $badge = 'bg-danger';
            ?>
            <div class="col-md-6">
                <div class="glass-panel p-4 h-100 position-relative">
                    <span class="position-absolute top-0 end-0 badge <?php echo $badge; ?> m-3"><?php echo $b['status']; ?></span>
                    
                    <h5 class="text-info mb-1">PNR: <?php echo htmlspecialchars($b['pnr']); ?></h5>
                    <p class="text-muted small mb-3">Booked on: <?php echo date('M d, Y', strtotime($b['booking_date'])); ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-dark rounded border border-secondary">
                        <div class="text-center">
                            <h5 class="text-white mb-0"><?php echo date('H:i', strtotime($b['departure_time'])); ?></h5>
                            <small class="text-muted"><?php echo $b['orig_city']; ?></small>
                        </div>
                        <i class="bi bi-airplane text-info"></i>
                        <div class="text-center">
                            <h5 class="text-white mb-0"><?php echo date('H:i', strtotime($b['arrival_time'])); ?></h5>
                            <small class="text-muted"><?php echo $b['dest_city']; ?></small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-end mt-3">
                        <div>
                            <span class="text-muted d-block small">Flight</span>
                            <strong class="text-light"><?php echo $b['flight_number']; ?></strong>
                        </div>
                        <div>
                            <span class="text-muted d-block small">Amount</span>
                            <strong class="text-info">$<?php echo number_format($b['total_price'], 2); ?></strong>
                        </div>
                        <div>
                            <?php if($b['status'] == 'Confirmed'): ?>
                            <a href="ticket.php?pnr=<?php echo $b['pnr']; ?>" class="btn btn-outline-info btn-sm">View Ticket</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
