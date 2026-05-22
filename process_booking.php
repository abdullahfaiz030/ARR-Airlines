<?php
require_once 'config.php';

if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_SESSION['pending_booking'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$pending = $_SESSION['pending_booking'];

// Generate unique PNR
$pnr = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

$conn->begin_transaction();

try {
    // 1. Insert Booking
    $stmt_booking = $conn->prepare("INSERT INTO bookings (user_id, flight_id, total_price, status, pnr) VALUES (?, ?, ?, 'Confirmed', ?)");
    $stmt_booking->bind_param("iids", $user_id, $pending['flight_id'], $pending['total_price'], $pnr);
    $stmt_booking->execute();
    $booking_id = $conn->insert_id;
    $stmt_booking->close();

    // 2. Insert Passengers
    $stmt_pax = $conn->prepare("INSERT INTO passengers (booking_id, name, dob, passport, seat_number) VALUES (?, ?, ?, ?, ?)");
    foreach ($pending['pax_data'] as $pax) {
        $stmt_pax->bind_param("issss", $booking_id, $pax['name'], $pax['dob'], $pax['passport'], $pax['seat']);
        $stmt_pax->execute();
    }
    $stmt_pax->close();

    // 3. Insert Payment
    $tx_id = "TXN" . strtoupper(uniqid());
    $stmt_pay = $conn->prepare("INSERT INTO payments (booking_id, amount, payment_method, status, transaction_id) VALUES (?, ?, 'Credit Card (Mock)', 'Success', ?)");
    $stmt_pay->bind_param("ids", $booking_id, $pending['total_price'], $tx_id);
    $stmt_pay->execute();
    $stmt_pay->close();

    $conn->commit();

    // Clear pending booking
    unset($_SESSION['pending_booking']);

    // Redirect to ticket
    header("Location: ticket.php?pnr=" . $pnr);
    exit;

} catch (Exception $e) {
    $conn->rollback();
    die("Booking failed: " . $e->getMessage());
}
?>
