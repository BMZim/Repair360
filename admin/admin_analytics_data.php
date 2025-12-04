<?php
include("config.php");

// Total Confirmed Bookings
$bookingQuery = $conn->query("
    SELECT COUNT(*) AS total 
    FROM appointments 
    WHERE status = 'Confirmed' OR status = 'Completed'
");
$bookingData = $bookingQuery->fetch_assoc();
$totalBookings = $bookingData['total'] ?? 0;

// Total Users (customer + mechanic)
$userQuery = $conn->query("
    SELECT 
        (SELECT COUNT(*) FROM customer) + 
        (SELECT COUNT(*) FROM mechanic) AS users
");
$userData = $userQuery->fetch_assoc();
$totalUsers = $userData['users'] ?? 0;

// Total Revenue
$revenueQuery = $conn->query("
    SELECT SUM(amount) AS revenue 
    FROM revenue
");
$revenueData = $revenueQuery->fetch_assoc();
$totalRevenue = $revenueData['revenue'] ?? 0;

// Monthly Bookings by month
$monthlyBookingQuery = $conn->query("
    SELECT 
        DATE_FORMAT(appointment_date, '%Y-%m') AS month,
        COUNT(*) AS total
    FROM appointments
    GROUP BY month
    ORDER BY month
");

$monthlyBookings = [];
while ($row = $monthlyBookingQuery->fetch_assoc()) {
    $monthlyBookings[] = $row;
}

// Monthly Revenue by month
$monthlyRevenueQuery = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') AS month,
        SUM(amount) AS revenue
    FROM revenue
    GROUP BY month
    ORDER BY month
");

$monthlyRevenue = [];
while ($row = $monthlyRevenueQuery->fetch_assoc()) {
    $monthlyRevenue[] = $row;
}

// Response JSON
echo json_encode([
    "totalBookings" => $totalBookings,
    "totalUsers" => $totalUsers,
    "totalRevenue" => $totalRevenue,
    "monthlyBookings" => $monthlyBookings,
    "monthlyRevenue" => $monthlyRevenue
]);
?>
