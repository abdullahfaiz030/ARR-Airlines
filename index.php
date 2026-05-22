<?php
require_once 'config.php';

// Fetch airports for the search form
$airports = [];
$res = $conn->query("SELECT id, code, city FROM airports ORDER BY city ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $airports[] = $row;
    }
}

include 'header.php';
?>

<!-- Hero Section with Flight Search -->
<section class="hero-section text-center py-5">
    <div class="container mt-5">
        <h1 style="background: linear-gradient(90deg, #00c6ff 0%, #0072ff 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-transform: uppercase; letter-spacing: 3px; font-size: 4rem; text-shadow: 0px 0px 20px rgba(0, 198, 255, 0.3); font-weight: 800;">LUXURY CLASS TRAVEL</h1>
        <h2 style="color: #e0f7fa; font-weight: 300; letter-spacing: 2px; font-size: 1.8rem; text-shadow: 0px 0px 10px rgba(0, 198, 255, 0.4);">Experience the pinnacle of air travel with ARR Airlines</h2>
        
        <div class="glass-panel mt-5 mx-auto text-start" style="max-width: 900px; overflow: hidden;">
            <!-- Tabs Header -->
            <ul class="nav nav-tabs px-3 pt-3" style="border-bottom: 1px solid rgba(255,215,0,0.3);" id="bookingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" style="color: #00c6ff; background: transparent; border: none; border-bottom: 3px solid #00c6ff; font-size: 1.1rem; border-radius: 0;" id="book-tab" data-bs-toggle="tab" data-bs-target="#book" type="button" role="tab"><i class="bi bi-airplane-engines"></i> Book a Flight</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" style="color: #fff; background: transparent; border: none; border-bottom: 3px solid transparent; font-size: 1.1rem; border-radius: 0;" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage" type="button" role="tab"><i class="bi bi-journal-bookmark"></i> Manage Booking</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" style="color: #fff; background: transparent; border: none; border-bottom: 3px solid transparent; font-size: 1.1rem; border-radius: 0;" id="checkin-tab" data-bs-toggle="tab" data-bs-target="#checkin" type="button" role="tab"><i class="bi bi-person-check"></i> Check-in</button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content p-4" id="bookingTabsContent">
                <!-- Book a Flight Tab -->
                <div class="tab-pane fade show active" id="book" role="tabpanel">
                    <form action="search_results.php" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">From</label>
                            <select name="origin" class="form-select" required>
                                <option value="">Leaving from...</option>
                                <?php foreach($airports as $a): ?>
                                    <option value="<?php echo $a['id']; ?>"><?php echo $a['city']." (".$a['code'].")"; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">To</label>
                            <select name="dest" class="form-select" required>
                                <option value="">Going to...</option>
                                <?php foreach($airports as $a): ?>
                                    <option value="<?php echo $a['id']; ?>"><?php echo $a['city']." (".$a['code'].")"; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Departure Date</label>
                            <input type="date" name="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Passengers</label>
                            <input type="number" name="passengers" class="form-control" min="1" max="9" value="1" required>
                        </div>
                        <div class="col-12 mt-4 text-center">
                            <button type="submit" class="btn btn-custom px-5 fs-5 w-100">SEARCH FLIGHTS</button>
                        </div>
                    </form>
                </div>

                <!-- Manage Booking Tab -->
                <div class="tab-pane fade" id="manage" role="tabpanel">
                    <div class="text-center py-4">
                        <i class="bi bi-lock text-info display-4 mb-3"></i>
                        <h5>Access Your Bookings</h5>
                        <p class="text-muted">Please log in to manage your upcoming flights, change seats, or request upgrades.</p>
                        <a href="login.php" class="btn btn-outline-info mt-2">Login to Manage</a>
                    </div>
                </div>

                <!-- Check-in Tab -->
                <div class="tab-pane fade" id="checkin" role="tabpanel">
                    <form class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Booking Reference (PNR)</label>
                            <input type="text" class="form-control" placeholder="e.g. A3B89F" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" placeholder="Passenger Last Name" required>
                        </div>
                        <div class="col-12 mt-4 text-center">
                            <button type="button" onclick="alert('Online check-in opens 24 hours before departure.');" class="btn btn-custom px-5 fs-5 w-100">CHECK-IN NOW</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <script>
            // Simple script to handle tab styling changes
            document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.nav-tabs .nav-link').forEach(t => {
                        t.style.color = '#fff';
                        t.style.borderBottomColor = 'transparent';
                    });
                    this.style.color = '#00c6ff';
                    this.style.borderBottomColor = '#00c6ff';
                });
            });
        </script>
    </div>
</section>

<!-- Destinations Section -->
<section class="container py-5 mt-4">
    <h2 class="text-center mb-5" style="color: #00c6ff;">Explore Premium Destinations</h2>
    <div class="row g-4">
        <!-- New York -->
        <div class="col-md-4">
            <div class="glass-panel h-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=800&q=80" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="New York">
                <div class="p-4">
                    <h5 style="color: #00c6ff;">New York (JFK)</h5>
                    <p>Experience the bustling energy of the Big Apple with luxury comfort.</p>
                </div>
            </div>
        </div>
        <!-- Paris -->
        <div class="col-md-4">
            <div class="glass-panel h-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=800&q=80" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="Paris">
                <div class="p-4">
                    <h5 style="color: #00c6ff;">Paris (CDG)</h5>
                    <p>Discover the city of love, fashion, and unparalleled culinary delights.</p>
                </div>
            </div>
        </div>
        <!-- Tokyo -->
        <div class="col-md-4">
            <div class="glass-panel h-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800&q=80" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="Tokyo">
                <div class="p-4">
                    <h5 style="color: #00c6ff;">Tokyo (HND)</h5>
                    <p>Immerse yourself in a vibrant blend of traditional culture and future tech.</p>
                </div>
            </div>
        </div>
        <!-- Dubai -->
        <div class="col-md-4">
            <div class="glass-panel h-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="Dubai">
                <div class="p-4">
                    <h5 style="color: #00c6ff;">Dubai (DXB)</h5>
                    <p>Marvel at stunning architecture, luxury shopping, and golden desert dunes.</p>
                </div>
            </div>
        </div>
        <!-- London -->
        <div class="col-md-4">
            <div class="glass-panel h-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1520939817895-060bdaf4fe1b?w=800&q=80" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="London">
                <div class="p-4">
                    <h5 style="color: #00c6ff;">London (LHR)</h5>
                    <p>Explore historic landmarks, royal palaces, and world-class museums.</p>
                </div>
            </div>
        </div>
        <!-- Singapore -->
        <div class="col-md-4">
            <div class="glass-panel h-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1525625293386-3f8f99389edd?w=800&q=80" class="img-fluid w-100" style="height: 200px; object-fit: cover;" alt="Singapore">
                <div class="p-4">
                    <h5 style="color: #00c6ff;">Singapore (SIN)</h5>
                    <p>Wander through lush futuristic gardens and enjoy an immaculate cityscape.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="container py-5 mb-5">
    <h2 class="text-center mb-5" style="color: #00c6ff;">Our Signature Services</h2>
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="glass-panel p-4 h-100">
                <i class="bi bi-star-fill display-4 mb-3" style="color: #00c6ff;"></i>
                <h5 style="color: #00c6ff;">First-Class Comfort</h5>
                <p>Enjoy spacious seats, gourmet meals, and personalized service on every flight.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel p-4 h-100">
                <i class="bi bi-cup-hot-fill display-4 mb-3" style="color: #00c6ff;"></i>
                <h5 style="color: #00c6ff;">Exclusive Lounges</h5>
                <p>Relax in our luxurious airport lounges with premium amenities before your flight.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-panel p-4 h-100">
                <i class="bi bi-clock-history display-4 mb-3" style="color: #00c6ff;"></i>
                <h5 style="color: #00c6ff;">Priority Boarding</h5>
                <p>Board first and settle in with ease, ensuring a stress-free start to your journey.</p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
