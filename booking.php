<?php
require_once 'config.php';

// Require login
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['flight_id'])) {
    header("Location: index.php");
    exit;
}

$flight_id = (int)$_POST['flight_id'];
$passengers = (int)$_POST['passengers'];

// Fetch flight details
$stmt = $conn->prepare("SELECT f.flight_number, f.base_price, o.city as orig_city, d.city as dest_city 
                        FROM flights f 
                        JOIN airports o ON f.origin_id = o.id 
                        JOIN airports d ON f.dest_id = d.id 
                        WHERE f.id = ?");
$stmt->bind_param("i", $flight_id);
$stmt->execute();
$flight = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$flight) {
    die("Flight not found.");
}

$total_price = $flight['base_price'] * $passengers;

include 'header.php';
?>

<div class="container py-5">
    <h2 class="mb-4" style="color: #00c6ff;"><i class="bi bi-people-fill"></i> Passenger Details</h2>
    <div class="row">
        <!-- Passenger Form -->
        <div class="col-md-8">
            <div class="glass-panel p-4 mb-4">
                <form action="checkout.php" method="POST" id="bookingForm">
                    <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">
                    <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

                    <?php for($i = 1; $i <= $passengers; $i++): ?>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <h5 style="color: #00c6ff;">Passenger <?php echo $i; ?></h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name_<?php echo $i; ?>" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob_<?php echo $i; ?>" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Passport / ID Number</label>
                                <input type="text" name="passport_<?php echo $i; ?>" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Seat (e.g. 12A)</label>
                                <input type="text" name="seat_<?php echo $i; ?>" class="form-control" required placeholder="Choose from seat map below">
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <div class="text-end">
                        <button type="submit" class="btn btn-custom px-5 py-2 fs-5">Proceed to Payment <i class="bi bi-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary & Seat Map -->
        <div class="col-md-4">
            <!-- Flight Summary -->
            <div class="glass-panel p-4 mb-4">
                <h5 style="color: #00c6ff; border-bottom: 1px solid rgba(255,215,0,0.3); padding-bottom: 10px;">Booking Summary</h5>
                <p class="mb-1 fw-bold fs-5"><?php echo $flight['orig_city']; ?> &rarr; <?php echo $flight['dest_city']; ?></p>
                <p class="text-muted mb-3">Flight: <?php echo $flight['flight_number']; ?></p>
                <div class="d-flex justify-content-between mb-2">
                    <span>Passengers:</span>
                    <span><?php echo $passengers; ?> x $<?php echo number_format($flight['base_price'], 2); ?></span>
                </div>
                <hr style="border-color: rgba(255,215,0,0.5);">
                <div class="d-flex justify-content-between fs-5 fw-bold text-info">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total_price, 2); ?></span>
                </div>
            </div>

            <!-- Visual Seat Map (Mock) -->
            <div class="glass-panel p-4 text-center">
                <h5 style="color: #00c6ff;" class="mb-3">Seat Map</h5>
                <div class="d-flex justify-content-center flex-column align-items-center bg-dark p-3 rounded border border-secondary" style="max-width: 200px; margin: 0 auto;">
                    <div class="w-100 bg-secondary rounded-top" style="height: 30px; margin-bottom: 15px; clip-path: polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%);"></div>
                    <?php 
                    $rows = 5;
                    $cols = ['A', 'B', ' ', 'C', 'D'];
                    for($r=1; $r<=$rows; $r++) {
                        echo '<div class="d-flex justify-content-center gap-1 mb-2">';
                        foreach($cols as $c) {
                            if($c == ' ') {
                                echo '<div style="width: 20px;"></div>'; // Aisle
                            } else {
                                $seat = $r.$c;
                                echo "<div class='border border-info rounded text-white d-flex align-items-center justify-content-center' style='width: 30px; height: 30px; font-size: 0.7rem; cursor:pointer;' onclick='selectSeat(\"$seat\")'>$seat</div>";
                            }
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
                <small class="text-muted mt-2 d-block">Click a seat to assign. Note: Mock layout.</small>
            </div>
        </div>
    </div>
</div>

<script>
    function selectSeat(seatNum) {
        // Find first empty seat input
        let inputs = document.querySelectorAll('input[name^="seat_"]');
        for(let input of inputs) {
            if(input.value === '') {
                input.value = seatNum;
                break;
            }
        }
    }
</script>

<?php include 'footer.php'; ?>
