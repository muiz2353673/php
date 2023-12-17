<?php
//for diplaying errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Retrieve owner name from the URL
$owner_name = $_GET['owner'];

// Connect to the MySQL database
$servername = "localhost";
$username = "u2353673";
$password = "AM03oct23am";
$database = "u2353673";

$conn = new mysqli($servername, $username, $password, $database);
// (similar to the connection code in index.php)

// Query to get owner details
$query = "SELECT * FROM owners WHERE name = '$owner_name'";
$result = $conn->query($query);

// Display owner details
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h2>Owner Details</h2>";
    echo "<p>Name: " . $row["name"] ."</p>";
    echo "<p>Email: " . $row["email"] ."</p>";
    echo "<p>Address: ". $row["address"]."</p>";
    echo "<p>Phone number :". $row["phone"]."</p>";
    // Add additional details if needed
} else {
    echo "Owner not found";
}

// Close the connection
$conn->close();
?>
