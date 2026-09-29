<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "railwaydb"
);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>