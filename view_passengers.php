<?php

include "db.php";

$result = $conn->query(
    "SELECT Passenger_ID, Name, Age, Gender, Mobile
     FROM passenger
     ORDER BY Passenger_ID"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>View Passengers</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

    <div class="logo">🚆 RailConnect</div>

    <div>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="book.php">Book Ticket</a>
    </div>

</div>


<div class="container">

<h2>👥 Passenger Details</h2>

<p>
Registered passengers in the Railway Management System
</p>


<div class="table-container">

<table>

<tr>

<th>Passenger ID</th>
<th>Name</th>
<th>Age</th>
<th>Gender</th>
<th>Mobile</th>

</tr>


<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

?>

<tr>

<td>
<?php echo $row["Passenger_ID"]; ?>
</td>

<td>
<?php echo htmlspecialchars($row["Name"]); ?>
</td>

<td>
<?php echo $row["Age"]; ?>
</td>

<td>
<?php echo $row["Gender"]; ?>
</td>

<td>
<?php echo htmlspecialchars($row["Mobile"]); ?>
</td>

</tr>

<?php

    }

} else {

?>

<tr>

<td colspan="5">
No passengers registered yet.
</td>

</tr>

<?php

}

?>

</table>

</div>

</div>


<div class="footer">

🚆 RailConnect Railway Management System © 2026

</div>

</body>

</html>