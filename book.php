<?php

include "db.php";

$message = "";

if (isset($_POST["book"])) {

    $passenger_id = $_POST["passenger_id"];
    $train_id = $_POST["train_id"];
    $journey_date = $_POST["journey_date"];
    $seat_no = $_POST["seat_no"];
    $coach_no = $_POST["coach_no"];
    $class = $_POST["class"];
    $fare = $_POST["fare"];
    $payment_mode = $_POST["payment_mode"];

    $result = $conn->query(
        "SELECT MAX(Ticket_No) AS max_ticket FROM ticket"
    );

    $row = $result->fetch_assoc();

    $ticket_no = ($row["max_ticket"] == NULL)
        ? 501
        : $row["max_ticket"] + 1;

    $pnr = "PNR" . date("ymd") . $ticket_no;

    $check = $conn->query(
        "SELECT * FROM ticket
         WHERE Train_ID='$train_id'
         AND Journey_Date='$journey_date'
         AND Seat_No='$seat_no'"
    );

    if ($check->num_rows > 0) {

        $message = "❌ This seat is already booked.";

    } else {

        $sql = "INSERT INTO ticket
                (Ticket_No, Passenger_ID, Train_ID,
                 Journey_Date, Seat_No, Coach_No,
                 Class, Fare, PNR, Booking_Status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Confirmed')";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "iiisssdss",
            $ticket_no,
            $passenger_id,
            $train_id,
            $journey_date,
            $seat_no,
            $coach_no,
            $class,
            $fare,
            $pnr
        );

        if ($stmt->execute()) {

            $result2 = $conn->query(
                "SELECT MAX(Payment_ID) AS max_payment
                 FROM payment"
            );

            $row2 = $result2->fetch_assoc();

            $payment_id = ($row2["max_payment"] == NULL)
                ? 9001
                : $row2["max_payment"] + 1;

            $payment_sql = "INSERT INTO payment
                (Payment_ID, Ticket_No, Payment_Date,
                 Amount, Payment_Mode, Payment_Status)
                VALUES (?, ?, CURDATE(), ?, ?, 'Paid')";

            $payment_stmt = $conn->prepare($payment_sql);

            $payment_stmt->bind_param(
                "iids",
                $payment_id,
                $ticket_no,
                $fare,
                $payment_mode
            );

            if ($payment_stmt->execute()) {

                $message =
                    "✅ Booking Successful!<br>
                     PNR: <b>$pnr</b><br>
                     Ticket No: <b>$ticket_no</b>";

            }
        }
    }
}

$passengers = $conn->query(
    "SELECT Passenger_ID, Name FROM passenger"
);

$trains = $conn->query(
    "SELECT Train_ID, Train_Name, Source, Destination
     FROM train"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Book Ticket</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

<div class="logo">🚆 RailConnect</div>

<a href="index.php">Home</a>

</div>

<div class="form-box">

<h2>🎫 Book Railway Ticket</h2>

<?php

if ($message != "") {
    echo "<div class='success'>$message</div>";
}

?>

<form method="POST">

<label>Passenger</label>

<select name="passenger_id" required>

<option value="">Select Passenger</option>

<?php

while ($p = $passengers->fetch_assoc()) {

    echo "<option value='".$p["Passenger_ID"]."'>";

    echo $p["Passenger_ID"]." - ".$p["Name"];

    echo "</option>";

}

?>

</select>


<label>Train</label>

<select name="train_id" required>

<option value="">Select Train</option>

<?php

while ($t = $trains->fetch_assoc()) {

    echo "<option value='".$t["Train_ID"]."'>";

    echo $t["Train_Name"] .
         " (" .
         $t["Source"] .
         " → " .
         $t["Destination"] .
         ")";

    echo "</option>";

}

?>

</select>


<label>Journey Date</label>

<input type="date"
       name="journey_date"
       min="<?php echo date('Y-m-d'); ?>"
       required>


<label>Seat Number</label>

<input type="text"
       name="seat_no"
       placeholder="Example: S1"
       required>


<label>Coach Number</label>

<input type="text"
       name="coach_no"
       placeholder="Example: C1"
       required>


<label>Class</label>

<select name="class" required>

<option value="Sleeper">Sleeper</option>

<option value="AC">AC</option>

</select>


<label>Fare</label>

<input type="number"
       name="fare"
       min="1"
       placeholder="Enter Fare"
       required>


<label>Payment Mode</label>

<select name="payment_mode" required>

<option value="">Select Payment</option>

<option value="UPI">UPI</option>

<option value="Card">Card</option>

<option value="Cash">Cash</option>

</select>


<button class="btn"
        type="submit"
        name="book">

BOOK TICKET & PAY

</button>

</form>

</div>

</body>

</html>