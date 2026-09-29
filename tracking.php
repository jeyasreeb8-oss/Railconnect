<?php

include "db.php";

$train = null;

if (isset($_POST["track"])) {

    $train_id = $_POST["train_id"];

    $stmt = $conn->prepare(
        "SELECT Train_Name, Source, Destination,
                Current_Location, Train_Status,
                Next_Station, Last_Updated
         FROM train
         WHERE Train_ID = ?"
    );

    $stmt->bind_param("i", $train_id);

    $stmt->execute();

    $result = $stmt->get_result();

    $train = $result->fetch_assoc();
}

$trains = $conn->query(
    "SELECT Train_ID, Train_Name FROM train"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Train Tracking</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

<div class="logo">🚆 RailConnect</div>

<a href="index.php">Home</a>

</div>

<div class="form-box">

<h2>📍 Track Your Train</h2>

<form method="POST">

<label>Select Train</label>

<select name="train_id" required>

<option value="">Select Train</option>

<?php

while ($t = $trains->fetch_assoc()) {

    echo "<option value='".$t["Train_ID"]."'>";

    echo $t["Train_Name"];

    echo "</option>";

}

?>

</select>

<button class="btn"
        type="submit"
        name="track">

TRACK TRAIN

</button>

</form>

</div>


<?php if ($train) { ?>

<div class="container">

<div class="track">

<h2>
🚆 <?php echo $train["Train_Name"]; ?>
</h2>

<p>

<strong>Route:</strong>

<?php echo $train["Source"]; ?>

→

<?php echo $train["Destination"]; ?>

</p>

<p>

📍 <strong>Current Location:</strong>

<?php echo $train["Current_Location"]; ?>

</p>

<p>

🚉 <strong>Next Station:</strong>

<?php echo $train["Next_Station"]; ?>

</p>

<p>

🟢 <strong>Status:</strong>

<?php echo $train["Train_Status"]; ?>

</p>

<p>

🕐 <strong>Last Updated:</strong>

<?php echo $train["Last_Updated"]; ?>

</p>

</div>

</div>

<?php } ?>

</body>

</html>