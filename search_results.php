<?php
require_once 'config.php';

$origin = isset($_GET['origin']) ? (int)$_GET['origin'] : 0;
$dest = isset($_GET['dest']) ? (int)$_GET['dest'] : 0;
$date = isset($_GET['date']) ? $_GET['date'] : '';
$passengers = isset($_GET['passengers']) ? (int)$_GET['passengers'] : 1;

$error = '';
$flights = [];

if ($origin && $dest && $date) {
    if ($origin === $dest) {
        $error = "Origin and destination cannot be the same.";
    } else {
        // Query flights
        $stmt = $conn->prepare("SELECT f.id, f.flight_number, f.departure_time, f.arrival_time, f.base_price, 
                                o.city as orig_city, o.code as orig_code, 
                                d.city as dest_city, d.code as dest_code 
                                FROM flights f 
                                JOIN airports o ON f.origin_id = o.id 
                                JOIN airports d ON f.dest_id = d.id 
                                WHERE f.origin_id = ? AND f.dest_id = ? AND DATE(f.departure_time) = ?");
        $stmt->bind_param("iis", $origin, $dest, $date);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $flights[] = $row;
        }
        $stmt->close();
    }
} else {
    $error = "Please provide all search criteria.";
}

include 'header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #00c6ff; text-transform: uppercase;"><i class="bi bi-airplane"></i> Search Results</h2>
        <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill px-3">Modify Search</a>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger" style="background: rgba(220,53,69,0.2); color: #ff6b6b; border: 1px solid #dc3545;"><?php echo $error; ?></div>
    <?php elseif(empty($flights)): ?>
        <div class="glass-panel p-5 text-center">
            <i class="bi bi-emoji-frown display-1 mb-3 text-muted"></i>
            <h3 class="text-white">No Flights Found</h3>
            <p class="text-muted">We couldn't find any flights matching your criteria. Try changing the date or destination.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($flights as $f): 
                $dep_time = date('H:i', strtotime($f['departure_time']));
                $arr_time = date('H:i', strtotime($f['arrival_time']));
                $duration = (strtotime($f['arrival_time']) - strtotime($f['departure_time'])) / 60; // in minutes
                $hours = floor($duration / 60);
                $mins = $duration % 60;
                $total_price = $f['base_price'] * $passengers;
            ?>
            <div class="col-12">
                <div class="glass-panel p-4 d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div class="text-center text-md-start mb-3 mb-md-0" style="min-width: 150px;">
                        <h4 style="color: #00c6ff; margin-bottom: 0;"><?php echo $f['flight_number']; ?></h4>
                        <small class="text-muted">ARR Airlines</small>
                    </div>

                    <div class="d-flex align-items-center justify-content-center flex-grow-1 px-md-5 w-100 mb-3 mb-md-0">
                        <div class="text-end me-4">
                            <h3 class="mb-0"><?php echo $dep_time; ?></h3>
                            <div class="text-muted"><?php echo $f['orig_city']." (".$f['orig_code'].")"; ?></div>
                        </div>
                        <div class="text-center px-3" style="border-bottom: 2px dashed #00c6ff; position: relative;">
                            <i class="bi bi-airplane text-info position-absolute" style="top: -12px; left: 50%; transform: translateX(-50%);"></i>
                            <small class="text-muted"><?php echo $hours."h ".$mins."m"; ?></small>
                        </div>
                        <div class="text-start ms-4">
                            <h3 class="mb-0"><?php echo $arr_time; ?></h3>
                            <div class="text-muted"><?php echo $f['dest_city']." (".$f['dest_code'].")"; ?></div>
                        </div>
                    </div>

                    <div class="text-center text-md-end border-start border-info ps-md-4 ms-md-2" style="min-width: 200px;">
                        <h3 style="color: #00c6ff;" class="mb-1">$<?php echo number_format($f['base_price'], 2); ?> <small class="fs-6 text-muted">/ person</small></h3>
                        <div class="text-light mb-2 small">Total: $<?php echo number_format($total_price, 2); ?> for <?php echo $passengers; ?> pax</div>
                        
                        <form action="booking.php" method="POST">
                            <input type="hidden" name="flight_id" value="<?php echo $f['id']; ?>">
                            <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                            <button type="submit" class="btn btn-custom w-100">BOOK NOW</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
