<?php
// Connect to MySQL server
$conn = new mysqli("localhost", "root", "");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS project";
$conn->query($sql);
$conn->select_db("project");

// Drop existing tables to avoid schema conflicts
$conn->query("DROP TABLE IF EXISTS payments");
$conn->query("DROP TABLE IF EXISTS passengers");
$conn->query("DROP TABLE IF EXISTS bookings");
$conn->query("DROP TABLE IF EXISTS flights");
$conn->query("DROP TABLE IF EXISTS airports");
$conn->query("DROP TABLE IF EXISTS users");
$conn->query("DROP TABLE IF EXISTS ticket_booking");

// Helper function to execute queries
function executeQuery($conn, $sql, $success_msg) {
    if ($conn->query($sql) === TRUE) {
        // echo "<p style='color: green;'>$success_msg</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}

// 1. Users Table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
executeQuery($conn, $sql, "Table 'users' ready.");

// 2. Airports Table
$sql = "CREATE TABLE IF NOT EXISTS airports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL
)";
executeQuery($conn, $sql, "Table 'airports' ready.");

// 3. Flights Table
$sql = "CREATE TABLE IF NOT EXISTS flights (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flight_number VARCHAR(20) NOT NULL UNIQUE,
    origin_id INT NOT NULL,
    dest_id INT NOT NULL,
    departure_time DATETIME NOT NULL,
    arrival_time DATETIME NOT NULL,
    base_price DECIMAL(10, 2) NOT NULL,
    total_seats INT NOT NULL DEFAULT 60,
    FOREIGN KEY (origin_id) REFERENCES airports(id),
    FOREIGN KEY (dest_id) REFERENCES airports(id)
)";
executeQuery($conn, $sql, "Table 'flights' ready.");

// 4. Bookings Table
$sql = "CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    flight_id INT NOT NULL,
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Pending',
    pnr VARCHAR(20) UNIQUE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (flight_id) REFERENCES flights(id)
)";
executeQuery($conn, $sql, "Table 'bookings' ready.");

// 5. Passengers Table
$sql = "CREATE TABLE IF NOT EXISTS passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    dob DATE NOT NULL,
    passport VARCHAR(100) NOT NULL,
    seat_number VARCHAR(10) NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
)";
executeQuery($conn, $sql, "Table 'passengers' ready.");

// 6. Payments Table
$sql = "CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    status ENUM('Success', 'Failed') DEFAULT 'Success',
    transaction_id VARCHAR(100) UNIQUE NOT NULL,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
)";
executeQuery($conn, $sql, "Table 'payments' ready.");

// --- SEED DATA ---

// Create a huge array of global airports (One for almost every country)
$global_airports = [
    ['JFK', 'John F. Kennedy', 'New York', 'USA'],
    ['LHR', 'Heathrow', 'London', 'UK'],
    ['CDG', 'Charles de Gaulle', 'Paris', 'France'],
    ['HND', 'Haneda', 'Tokyo', 'Japan'],
    ['DXB', 'Dubai Int', 'Dubai', 'UAE'],
    ['SIN', 'Changi', 'Singapore', 'Singapore'],
    ['SYD', 'Kingsford Smith', 'Sydney', 'Australia'],
    ['YYZ', 'Pearson', 'Toronto', 'Canada'],
    ['FRA', 'Frankfurt', 'Frankfurt', 'Germany'],
    ['BOM', 'Chhatrapati Shivaji', 'Mumbai', 'India'],
    ['PEK', 'Capital Int', 'Beijing', 'China'],
    ['CAI', 'Cairo Int', 'Cairo', 'Egypt'],
    ['CPT', 'Cape Town Int', 'Cape Town', 'South Africa'],
    ['GIG', 'Galeão Int', 'Rio de Janeiro', 'Brazil'],
    ['MEX', 'Mexico City Int', 'Mexico City', 'Mexico'],
    ['EZE', 'Ministro Pistarini', 'Buenos Aires', 'Argentina'],
    ['SVO', 'Sheremetyevo', 'Moscow', 'Russia'],
    ['FCO', 'Leonardo da Vinci', 'Rome', 'Italy'],
    ['MAD', 'Adolfo Suárez', 'Madrid', 'Spain'],
    ['IST', 'Istanbul Int', 'Istanbul', 'Turkey'],
    ['ICN', 'Incheon Int', 'Seoul', 'South Korea'],
    ['BKK', 'Suvarnabhumi', 'Bangkok', 'Thailand'],
    ['KUL', 'Kuala Lumpur Int', 'Kuala Lumpur', 'Malaysia'],
    ['CGK', 'Soekarno-Hatta', 'Jakarta', 'Indonesia'],
    ['SCL', 'Arturo Merino Benitez', 'Santiago', 'Chile'],
    ['BOG', 'El Dorado Int', 'Bogota', 'Colombia'],
    ['LIM', 'Jorge Chavez', 'Lima', 'Peru'],
    ['NBO', 'Jomo Kenyatta', 'Nairobi', 'Kenya'],
    ['LOS', 'Murtala Muhammed', 'Lagos', 'Nigeria'],
    ['CMN', 'Mohammed V', 'Casablanca', 'Morocco'],
    ['TLV', 'Ben Gurion', 'Tel Aviv', 'Israel'],
    ['RUH', 'King Khalid', 'Riyadh', 'Saudi Arabia'],
    ['DOH', 'Hamad Int', 'Doha', 'Qatar'],
    ['KWI', 'Kuwait Int', 'Kuwait City', 'Kuwait'],
    ['AMM', 'Queen Alia', 'Amman', 'Jordan'],
    ['BEY', 'Rafic Hariri', 'Beirut', 'Lebanon'],
    ['KHI', 'Jinnah Int', 'Karachi', 'Pakistan'],
    ['DAC', 'Hazrat Shahjalal', 'Dhaka', 'Bangladesh'],
    ['CMB', 'Bandaranaike', 'Colombo', 'Sri Lanka'],
    ['KTM', 'Tribhuvan', 'Kathmandu', 'Nepal'],
    ['SGN', 'Tan Son Nhat', 'Ho Chi Minh City', 'Vietnam'],
    ['MNL', 'Ninoy Aquino', 'Manila', 'Philippines'],
    ['TPE', 'Taoyuan Int', 'Taipei', 'Taiwan'],
    ['AKL', 'Auckland', 'Auckland', 'New Zealand'],
    ['NAN', 'Nadi Int', 'Nadi', 'Fiji'],
    ['ZRH', 'Zurich', 'Zurich', 'Switzerland'],
    ['VIE', 'Vienna Int', 'Vienna', 'Austria'],
    ['AMS', 'Schiphol', 'Amsterdam', 'Netherlands'],
    ['BRU', 'Brussels', 'Brussels', 'Belgium'],
    ['CPH', 'Copenhagen', 'Copenhagen', 'Denmark'],
    ['OSL', 'Oslo Gardermoen', 'Oslo', 'Norway'],
    ['ARN', 'Stockholm Arlanda', 'Stockholm', 'Sweden'],
    ['HEL', 'Helsinki Vantaa', 'Helsinki', 'Finland'],
    ['ATH', 'Athens Int', 'Athens', 'Greece'],
    ['LIS', 'Lisbon', 'Lisbon', 'Portugal'],
    ['WAW', 'Chopin', 'Warsaw', 'Poland'],
    ['PRG', 'Vaclav Havel', 'Prague', 'Czech Republic'],
    ['BUD', 'Budapest Ferenc Liszt', 'Budapest', 'Hungary'],
    ['OTP', 'Henri Coanda', 'Bucharest', 'Romania'],
    ['SOF', 'Sofia', 'Sofia', 'Bulgaria'],
    ['BEG', 'Nikola Tesla', 'Belgrade', 'Serbia'],
    ['KBP', 'Boryspil', 'Kyiv', 'Ukraine'],
    ['MSQ', 'Minsk National', 'Minsk', 'Belarus'],
    ['ALA', 'Almaty Int', 'Almaty', 'Kazakhstan'],
    ['TAS', 'Islam Karimov', 'Tashkent', 'Uzbekistan'],
    ['GYD', 'Heydar Aliyev', 'Baku', 'Azerbaijan'],
    ['TBS', 'Tbilisi Int', 'Tbilisi', 'Georgia'],
    ['EVN', 'Zvartnots', 'Yerevan', 'Armenia'],
    ['IKA', 'Imam Khomeini', 'Tehran', 'Iran'],
    ['BGW', 'Baghdad Int', 'Baghdad', 'Iraq'],
    ['KBL', 'Kabul Int', 'Kabul', 'Afghanistan'],
    ['HAV', 'Jose Marti', 'Havana', 'Cuba'],
    ['SDQ', 'Las Americas', 'Santo Domingo', 'Dominican Republic'],
    ['SJU', 'Luis Munoz Marin', 'San Juan', 'Puerto Rico'],
    ['PTY', 'Tocumen Int', 'Panama City', 'Panama'],
    ['SJO', 'Juan Santamaria', 'San Jose', 'Costa Rica'],
    ['SAL', 'El Salvador Int', 'San Salvador', 'El Salvador'],
    ['GUA', 'La Aurora', 'Guatemala City', 'Guatemala'],
    ['TGU', 'Toncontin', 'Tegucigalpa', 'Honduras'],
    ['MGA', 'Augusto C. Sandino', 'Managua', 'Nicaragua'],
    ['UIO', 'Mariscal Sucre', 'Quito', 'Ecuador'],
    ['CCS', 'Simon Bolivar', 'Caracas', 'Venezuela'],
    ['VVI', 'Viru Viru', 'Santa Cruz', 'Bolivia'],
    ['ASU', 'Silvio Pettirossi', 'Asuncion', 'Paraguay'],
    ['MVD', 'Carrasco', 'Montevideo', 'Uruguay'],
    ['ACC', 'Kotoka Int', 'Accra', 'Ghana'],
    ['ABJ', 'Felix Houphouet Boigny', 'Abidjan', 'Ivory Coast'],
    ['DKR', 'Blaise Diagne', 'Dakar', 'Senegal'],
    ['BKO', 'Modibo Keita', 'Bamako', 'Mali'],
    ['OUA', 'Thomas Sankara', 'Ouagadougou', 'Burkina Faso'],
    ['NIM', 'Diori Hamani', 'Niamey', 'Niger'],
    ['NDJ', 'N\'Djamena Int', 'N\'Djamena', 'Chad'],
    ['KRT', 'Khartoum Int', 'Khartoum', 'Sudan'],
    ['ADD', 'Bole Int', 'Addis Ababa', 'Ethiopia'],
    ['JIB', 'Djibouti-Ambouli', 'Djibouti', 'Djibouti'],
    ['EBB', 'Entebbe Int', 'Kampala', 'Uganda'],
    ['KGL', 'Kigali Int', 'Kigali', 'Rwanda'],
    ['BJM', 'Melchior Ndadaye', 'Bujumbura', 'Burundi'],
    ['DAR', 'Julius Nyerere', 'Dar es Salaam', 'Tanzania'],
    ['LUN', 'Kenneth Kaunda', 'Lusaka', 'Zambia'],
    ['HRE', 'Robert Gabriel Mugabe', 'Harare', 'Zimbabwe'],
    ['GBE', 'Sir Seretse Khama', 'Gaborone', 'Botswana'],
    ['WDH', 'Hosea Kutako', 'Windhoek', 'Namibia'],
    ['LAD', 'Quatro de Fevereiro', 'Luanda', 'Angola'],
    ['FIH', 'N\'djili', 'Kinshasa', 'DR Congo'],
    ['LBV', 'Leon Mba', 'Libreville', 'Gabon'],
    ['DLA', 'Douala Int', 'Douala', 'Cameroon']
];

// Insert all 100+ global airports
$stmt_apt = $conn->prepare("INSERT INTO airports (code, name, city, country) VALUES (?, ?, ?, ?)");
foreach ($global_airports as $apt) {
    $stmt_apt->bind_param("ssss", $apt[0], $apt[1], $apt[2], $apt[3]);
    $stmt_apt->execute();
}
$stmt_apt->close();
echo "<p style='color: green;'>Successfully added over 100 international airports from around the world!</p>";

// Fetch the airport IDs to generate random flights
$ids = [];
$res = $conn->query("SELECT id FROM airports");
while ($row = $res->fetch_assoc()) {
    $ids[] = $row['id'];
}

// Generate Randomized Flights for the next 14 days
// To prevent timeout, we generate exactly 3 departing flights per airport per day.
$flight_counter = 1000;
$stmt_flt = $conn->prepare("INSERT INTO flights (flight_number, origin_id, dest_id, departure_time, arrival_time, base_price, total_seats) VALUES (?, ?, ?, ?, ?, ?, 150)");

for ($day = 0; $day < 14; $day++) {
    $date = date('Y-m-d', strtotime("+$day days"));
    
    foreach ($ids as $orig_id) {
        // Pick 3 random destinations for this airport today
        $dests_today = array_rand(array_flip($ids), 4); 
        $flights_created = 0;
        
        foreach ($dests_today as $dest_id) {
            if ($orig_id == $dest_id) continue;
            if ($flights_created >= 3) break;
            
            // Random time between 06:00 and 22:00
            $hour = str_pad(rand(6, 22), 2, "0", STR_PAD_LEFT);
            $minute = str_pad(rand(0, 59), 2, "0", STR_PAD_LEFT);
            $dep_time = "$date $hour:$minute:00";
            
            // Random duration 2 to 14 hours
            $duration = rand(2, 14);
            $arr_time = date('Y-m-d H:i:s', strtotime("$dep_time + $duration hours"));
            
            // Random price $150 to $1500
            $price = rand(150, 1500) + 0.99;
            
            $flight_number = "ARR" . $flight_counter++;
            
            $stmt_flt->bind_param("siissd", $flight_number, $orig_id, $dest_id, $dep_time, $arr_time, $price);
            $stmt_flt->execute();
            
            $flights_created++;
        }
    }
}
$stmt_flt->close();

// Create a default admin user (password is 'admin123')
$hashed_admin_pw = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("INSERT IGNORE INTO users (name, email, password, role) VALUES ('System Admin', 'admin@arrairlines.com', '$hashed_admin_pw', 'admin')");

echo "<h3>Setup Complete!</h3>";
echo "<p>Over 100 global airports and <b>thousands of randomized international flights</b> have been successfully generated for the next 14 days.</p>";
echo "<p><b>Admin Login:</b> admin@arrairlines.com | <b>Password:</b> admin123</p>";

$conn->close();
?>
