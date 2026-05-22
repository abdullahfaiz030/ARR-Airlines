<?php
require_once 'config.php';

// Check if admin is logged in
if (!isAdmin()) {
    header("Location: login.php");
    exit;
}

$success = '';
$error = '';

// Handle Adding a Flight
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_flight') {
    $fnum = trim($_POST['flight_number']);
    $orig = (int)$_POST['origin_id'];
    $dest = (int)$_POST['dest_id'];
    $dep = $_POST['departure_time'];
    $arr = $_POST['arrival_time'];
    $price = (float)$_POST['base_price'];
    $seats = (int)$_POST['total_seats'];

    if ($orig == $dest) {
        $error = "Origin and destination cannot be the same.";
    } else {
        $stmt = $conn->prepare("INSERT INTO flights (flight_number, origin_id, dest_id, departure_time, arrival_time, base_price, total_seats) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siissdi", $fnum, $orig, $dest, $dep, $arr, $price, $seats);
        if ($stmt->execute()) {
            $success = "Flight added successfully!";
        } else {
            $error = "Failed to add flight: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch Airports for Dropdown
$airports = [];
$res = $conn->query("SELECT id, code, name FROM airports");
while ($row = $res->fetch_assoc()) {
    $airports[] = $row;
}

// Fetch all flights
$flights = [];
$q_flights = "SELECT f.id, f.flight_number, o.code as origin, d.code as dest, f.departure_time, f.arrival_time, f.base_price, f.total_seats 
              FROM flights f 
              JOIN airports o ON f.origin_id = o.id 
              JOIN airports d ON f.dest_id = d.id 
              ORDER BY f.departure_time DESC";
$res_flights = $conn->query($q_flights);
while ($row = $res_flights->fetch_assoc()) {
    $flights[] = $row;
}

// Fetch all bookings
$bookings = [];
$q_bookings = "SELECT b.pnr, u.name as user_name, f.flight_number, b.booking_date, b.total_price, b.status 
               FROM bookings b 
               JOIN users u ON b.user_id = u.id 
               JOIN flights f ON b.flight_id = f.id 
               ORDER BY b.booking_date DESC";
$res_bookings = $conn->query($q_bookings);
while ($row = $res_bookings->fetch_assoc()) {
    $bookings[] = $row;
}

include 'header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: #00c6ff; text-transform: uppercase; letter-spacing: 1px;"><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
    </div>

    <?php if($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <ul class="nav nav-pills mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active btn-custom text-dark px-4 me-2" id="flights-tab" data-bs-toggle="tab" data-bs-target="#flights" type="button" role="tab">Manage Flights</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link btn-custom text-dark px-4" style="background: rgba(255,255,255,0.8);" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab" onclick="this.style.background='linear-gradient(45deg, #00c6ff, #0072ff)'; document.getElementById('flights-tab').style.background='rgba(255,255,255,0.8)'">All Bookings</button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        
        <!-- FLIGHTS TAB -->
        <div class="tab-pane fade show active" id="flights" role="tabpanel">
            <div class="row">
                <!-- Add Flight Form -->
                <div class="col-md-4 mb-4">
                    <div class="glass-panel p-4">
                        <h4 style="color:#00c6ff;">Add New Flight</h4>
                        <hr style="border-color:#00c6ff;">
                        <form action="admin.php" method="POST">
                            <input type="hidden" name="action" value="add_flight">
                            <div class="mb-2">
                                <label class="form-label">Flight Number</label>
                                <input type="text" name="flight_number" class="form-control" required placeholder="e.g. ARR404">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Origin</label>
                                <select name="origin_id" class="form-select" required>
                                    <option value="">Select Origin...</option>
                                    <?php foreach($airports as $a): ?>
                                        <option value="<?php echo $a['id']; ?>"><?php echo $a['code']." - ".$a['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Destination</label>
                                <select name="dest_id" class="form-select" required>
                                    <option value="">Select Destination...</option>
                                    <?php foreach($airports as $a): ?>
                                        <option value="<?php echo $a['id']; ?>"><?php echo $a['code']." - ".$a['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Departure</label>
                                <input type="datetime-local" name="departure_time" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Arrival</label>
                                <input type="datetime-local" name="arrival_time" class="form-control" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Base Price ($)</label>
                                <input type="number" step="0.01" name="base_price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Total Seats</label>
                                <input type="number" name="total_seats" class="form-control" value="60" required>
                            </div>
                            <button type="submit" class="btn btn-info w-100 fw-bold">ADD FLIGHT</button>
                        </form>
                    </div>
                </div>

                <!-- Flight List -->
                <div class="col-md-8">
                    <div class="glass-panel p-4">
                        <h4 style="color:#00c6ff;">Flight Roster</h4>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle" style="background:transparent;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #00c6ff;">
                                        <th>Flight</th>
                                        <th>Route</th>
                                        <th>Departure</th>
                                        <th>Price</th>
                                        <th>Seats</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($flights as $f): ?>
                                    <tr>
                                        <td class="text-info fw-bold"><?php echo htmlspecialchars($f['flight_number']); ?></td>
                                        <td><?php echo $f['origin']." &rarr; ".$f['dest']; ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($f['departure_time'])); ?></td>
                                        <td>$<?php echo number_format($f['base_price'], 2); ?></td>
                                        <td><?php echo $f['total_seats']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOOKINGS TAB -->
        <div class="tab-pane fade" id="bookings" role="tabpanel">
            <div class="glass-panel p-4">
                <h4 style="color:#00c6ff;">All System Bookings</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr style="border-bottom: 2px solid #00c6ff;">
                                <th>PNR</th>
                                <th>Customer</th>
                                <th>Flight</th>
                                <th>Date Booked</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bookings as $b): ?>
                            <tr>
                                <td class="text-info fw-bold"><?php echo htmlspecialchars($b['pnr']); ?></td>
                                <td><?php echo htmlspecialchars($b['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($b['flight_number']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($b['booking_date'])); ?></td>
                                <td>$<?php echo number_format($b['total_price'], 2); ?></td>
                                <td>
                                    <?php 
                                        $badge = 'bg-secondary';
                                        if($b['status'] == 'Confirmed') $badge = 'bg-success';
                                        if($b['status'] == 'Cancelled') $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $badge; ?>"><?php echo $b['status']; ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Tab color toggling script
    document.getElementById('flights-tab').addEventListener('click', function() {
        this.style.background = 'linear-gradient(45deg, #00c6ff, #0072ff)';
        document.getElementById('bookings-tab').style.background = 'rgba(255,255,255,0.8)';
    });
</script>

<?php include 'footer.php'; ?>
