<?php

session_start();

include "db.php";

$message = "";

if (isset($_POST["login"])) {

    $mobile = trim($_POST["mobile"]);
    $password = $_POST["password"];

    $sql = "SELECT Passenger_ID, Name, Mobile, Password
            FROM passenger
            WHERE Mobile = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $mobile);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (
            !empty($user["Password"]) &&
            password_verify($password, $user["Password"])
        ) {

            $_SESSION["Passenger_ID"] =
                $user["Passenger_ID"];

            $_SESSION["Name"] =
                $user["Name"];

            header("Location: dashboard.php");

            exit();

        } else {

            $message = "❌ Incorrect password.";

        }

    } else {

        $message = "❌ Mobile number not registered.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Passenger Login</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

<div class="logo">🚆 RailConnect</div>

<div>

<a href="index.php">Home</a>

<a href="register.php">Register</a>

</div>

</div>


<div class="form-box login-box">

<div class="login-icon">
🚆
</div>

<h2>Passenger Login</h2>

<p class="subtitle">
Login to manage your railway bookings
</p>

<?php

if ($message != "") {

echo "<div class='error'>$message</div>";

}

?>

<form method="POST">

<label>Mobile Number</label>

<input
type="text"
name="mobile"
placeholder="Enter mobile number"
required
>


<label>Password</label>

<input
type="password"
name="password"
placeholder="Enter password"
required
>


<button
class="btn login-btn"
type="submit"
name="login"
>
LOGIN
</button>

</form>

<p class="form-link">

New passenger?

<a href="register.php">
Create Account
</a>

</p>

</div>

</body>

</html>