<?php

session_start();

include "db.php";

if (!isset($_SESSION["Passenger_ID"])) {

    header("Location: login.php");

    exit();

}

$passenger_id = $_SESSION["Passenger_ID"];


// Passenger details
$sql = "SELECT *
        FROM passenger
        WHERE Passenger_ID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $passenger_id);

$stmt->execute();

$passenger = $stmt->get_result()->fetch_assoc();


// Booking details
$sql = "SELECT
            t.Ticket_No,
            t.PNR,
            tr.Train_Name,
            tr.Source,
            tr.Destination,
            t.Journey_Date,
            t.Seat_No,
            t.Coach_No,
            t.Class,
            t.Fare,
            t.Booking_Status
        FROM ticket t
        JOIN train tr
        ON t.Train_ID = tr.Train_ID
        WHERE t.Passenger_ID = ?
        ORDER BY t.Journey_Date DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $passenger_id);

$stmt->execute();

$bookings = $stmt->get_result();


// Payment details
$sql = "SELECT
            pay.Payment_ID,
            pay.Ticket_No,
            pay.Payment_Date,
            pay.Amount,
            pay.Payment_Mode,
            pay.Payment_Status
        FROM payment pay
        JOIN ticket t
        ON pay.Ticket_No = t.Ticket_No
        WHERE t.Passenger_ID = ?
        ORDER BY pay.Payment_Date DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $passenger_id);

$stmt->execute();

$payments = $stmt->get_result();

?>

<!DOCTYPE html>

<html>

<head>

<title>My Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>


<div class="navbar">

<div class="logo">
🚆 RailConnect
</div>

<div>

<a href="index.php">Home</a>

<a href="book.php">Book Ticket</a>

<a href="pnr.php">PNR</a>

<a href="tracking.php">Track</a>

<a href="logout.php">Logout</a>

</div>

</div>


<div class="dashboard">

<div class="welcome">

<h1>
👋 Welcome, <?php echo htmlspecialchars($passenger["Name"]); ?>
</h1>

<p>
Manage your railway journey from one place.
</p>

</div>


<!-- Passenger Card -->

<div class="profile-card">

<h2>👤 My Profile</h2>

<div class="profile-grid">

<div>

<strong>Passenger ID</strong>

<span>
<?php echo $passenger["Passenger_ID"]; ?>
</span>

</div>


<div>

<strong>Name</strong>

<span>
<?php echo htmlspecialchars($passenger["Name"]); ?>
</span>

</div>


<div>

<strong>Age</strong>

<span>
<?php echo $passenger["Age"]; ?>
</span>

</div>


<div>

<strong>Gender</strong>

<span>
<?php echo $passenger["Gender"]; ?>
</span>

</div>


<div>

<strong>Mobile</strong>

<span>
<?php echo htmlspecialchars($passenger["Mobile"]); ?>
</span>

</div>

</div>

</div>


<!-- Quick Actions -->

<h2 class="section-title">
Quick Actions
</h2>

<div class="dashboard-cards">

<a href="book.php" class="dashboard-card">

<div class="dash-icon">🎫</div>

<h3>Book Ticket</h3>

<p>Book a new railway ticket.</p>

</a>


<a href="pnr.php" class="dashboard-card">

<div class="dash-icon">🔎</div>

<h3>PNR Status</h3>

<p>Check your ticket status.</p>

</a>


<a href="tracking.php" class="dashboard-card">

<div class="dash-icon">📍</div>

<h3>Track Train</h3>

<p>Check train location.</p>

</a>

</div>


<!-- Bookings -->

<div class="data-section">

<h2>🎫 My Bookings</h2>

<?php if ($bookings->num_rows > 0) { ?>

<div class="table-container">

<table>

<tr>

<th>PNR</th>
<th>Train</th>
<th>Route</th>
<th>Date</th>
<th>Seat</th>
<th>Class</th>
<th>Fare</th>
<th>Status</th>

</tr>


<?php while ($row = $bookings->fetch_assoc()) { ?>

<tr>

<td>
<strong><?php echo $row["PNR"]; ?></strong>
</td>

<td>
<?php echo htmlspecialchars($row["Train_Name"]); ?>
</td>

<td>
<?php echo htmlspecialchars($row["Source"]); ?>
→
<?php echo htmlspecialchars($row["Destination"]); ?>
</td>

<td>
<?php echo $row["Journey_Date"]; ?>
</td>

<td>
<?php echo $row["Coach_No"]; ?>
-
<?php echo $row["Seat_No"]; ?>
</td>

<td>
<?php echo $row["Class"]; ?>
</td>

<td>
₹<?php echo $row["Fare"]; ?>
</td>

<td>

<span class="badge">
<?php echo $row["Booking_Status"]; ?>
</span>

</td>

</tr>

<?php } ?>

</table>

</div>

<?php } else { ?>

<div class="empty-box">

🎫 No bookings found.

<br><br>

<a class="btn" href="book.php">
Book Your First Ticket
</a>

</div>

<?php } ?>

</div>


<!-- Payments -->

<div class="data-section">

<h2>💳 My Payments</h2>

<?php if ($payments->num_rows > 0) { ?>

<div class="table-container">

<table>

<tr>

<th>Payment ID</th>
<th>Ticket No</th>
<th>Date</th>
<th>Amount</th>
<th>Mode</th>
<th>Status</th>

</tr>


<?php while ($row = $payments->fetch_assoc()) { ?>

<tr>

<td>
<?php echo $row["Payment_ID"]; ?>
</td>

<td>
<?php echo $row["Ticket_No"]; ?>
</td>

<td>
<?php echo $row["Payment_Date"]; ?>
</td>

<td>
₹<?php echo $row["Amount"]; ?>
</td>

<td>
<?php echo $row["Payment_Mode"]; ?>
</td>

<td>

<span class="badge">
<?php echo $row["Payment_Status"]; ?>
</span>

</td>

</tr>

<?php } ?>

</table>

</div>

<?php } else { ?>

<div class="empty-box">

💳 No payment records found.

</div>

<?php } ?>

</div>


</div>


<div class="footer">

RailConnect Railway Management System © 2026

</div>


</body>

</html>