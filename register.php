<?php

include "db.php";

$message = "";

if (isset($_POST["register"])) {

    $name = trim($_POST["name"]);
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $mobile = trim($_POST["mobile"]);
    $password = $_POST["password"];

    // Check whether mobile already exists
    $check = $conn->prepare(
        "SELECT Passenger_ID FROM passenger WHERE Mobile = ?"
    );

    $check->bind_param("s", $mobile);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "❌ Mobile number already registered.";

    } else {

        // Generate Passenger ID
        $result = $conn->query(
            "SELECT MAX(Passenger_ID) AS max_id FROM passenger"
        );

        $row = $result->fetch_assoc();

        $id = ($row["max_id"] == NULL)
            ? 101
            : $row["max_id"] + 1;

        // Secure password
        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO passenger
                (Passenger_ID, Name, Age, Gender, Mobile, Password)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "isisss",
            $id,
            $name,
            $age,
            $gender,
            $mobile,
            $hashed_password
        );

        if ($stmt->execute()) {

            $message =
                "✅ Registration Successful!<br>
                 Passenger ID: <b>$id</b><br>
                 You can now login.";
        } else {

            $message = "❌ Registration Failed!";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Passenger Registration</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

    <div class="logo">🚆 RailConnect</div>

    <div>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </div>

</div>

<div class="form-box">

<h2>👤 Passenger Registration</h2>

<?php

if ($message != "") {

    echo "<div class='success'>$message</div>";

}

?>

<form method="POST">

<label>Full Name</label>

<input
    type="text"
    name="name"
    placeholder="Enter your name"
    required
>


<label>Age</label>

<input
    type="number"
    name="age"
    min="1"
    max="100"
    required
>


<label>Gender</label>

<select name="gender" required>

<option value="">Select Gender</option>

<option value="Male">Male</option>

<option value="Female">Female</option>

</select>


<label>Mobile Number</label>

<input
    type="text"
    name="mobile"
    maxlength="15"
    placeholder="Enter mobile number"
    required
>


<label>Password</label>

<input
    type="password"
    name="password"
    placeholder="Create password"
    minlength="4"
    required
>


<button
    class="btn"
    type="submit"
    name="register"
>
REGISTER
</button>

</form>

<p class="form-link">
Already registered?
<a href="login.php">Login here</a>
</p>

</div>

</body>

</html>