<?php

include "db.php";

$table = $_GET['table'];

$allowed = ["Passenger", "Train", "Ticket", "Payment", "Station"];

if (!in_array($table, $allowed)) {
    die("Invalid Table");
}

$result = $conn->query("SELECT * FROM $table");

?>

<!DOCTYPE html>
<html>
<head>
    <title>View <?php echo $table; ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2><?php echo $table; ?> Details</h2>

<a href="index.php">← Back</a>

<table>

<tr>
<?php
while ($field = $result->fetch_field()) {
    echo "<th>".$field->name."</th>";
}
?>
</tr>

<?php
while ($row = $result->fetch_assoc()) {

    echo "<tr>";

    foreach ($row as $value) {
        echo "<td>".$value."</td>";
    }

    echo "</tr>";
}
?>

</table>

</div>

</body>
</html>