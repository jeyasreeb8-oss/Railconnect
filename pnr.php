<?php

include "db.php";

$booking = null;

if (isset($_POST["search"])) {

    $pnr = trim($_POST["pnr"]);

    $sql = "SELECT
                t.PNR,
                t.Ticket_No,
                p.Name,
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

            JOIN passenger p
            ON t.Passenger_ID = p.Passenger_ID

            JOIN train tr
            ON t.Train_ID = tr.Train_ID

            WHERE t.PNR = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $pnr);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $booking = $result->fetch_assoc();

    }

}

?>

<!DOCTYPE html>
<html>

<head>

<title>PNR Status</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

<div class="logo">🚆 RailConnect</div>

<a href="index.php">Home</a>

</div>

<div class="container">

<div class="search-box">

<h2>🔎 PNR Status</h2>

<form method="POST">

<input type="text"
       name="pnr"
       placeholder="Enter PNR Number"
       required>

<button class="btn"
        type="submit"
        name="search">

SEARCH PNR

</button>

</form>

</div>


<?php if ($booking) { ?>

<div class="pnr-result">

<h2>🎫 Booking Details</h2>

<p>
<b>PNR:</b>
<?php echo $booking["PNR"]; ?>
</p>

<p>
<b>Passenger:</b>
<?php echo $booking["Name"]; ?>
</p>

<p>
<b>Train:</b>
<?php echo $booking["Train_Name"]; ?>
</p>

<p>
<b>Route:</b>
<?php echo $booking["Source"]; ?>
→
<?php echo $booking["Destination"]; ?>
</p>

<p>
<b>Journey Date:</b>
<?php echo $booking["Journey_Date"]; ?>
</p>

<p>
<b>Coach:</b>
<?php echo $booking["Coach_No"]; ?>
</p>

<p>
<b>Seat:</b>
<?php echo $booking["Seat_No"]; ?>
</p>

<p>
<b>Class:</b>
<?php echo $booking["Class"]; ?>
</p>

<p>
<b>Fare:</b>
₹<?php echo $booking["Fare"]; ?>
</p>

<p class="status">

Status:
<?php echo $booking["Booking_Status"]; ?>

</p>

</div>

<?php } elseif (isset($_POST["search"])) { ?>

<div class="error">

❌ PNR not found. Please check the PNR number.

</div>

<?php } ?>

</div>

</body>

</html>