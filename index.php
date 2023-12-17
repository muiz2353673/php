<?php
//for diplaying errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the MySQL database
$servername = "localhost";
$username = "u2353673";
$password = "AM03oct23am";
$database = "u2353673";

$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the required information
$query = "SELECT COUNT(DISTINCT id) as owners_count, COUNT(*) as dogs_count, COUNT(DISTINCT id) as events_count FROM dogs";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $owners_count = $row["owners_count"];
    $dogs_count = $row["dogs_count"];
    $events_count = $row["events_count"];
}

// Display the header
echo "<h1>Welcome to Poppleton Dog Show! This year $owners_count owners entered $dogs_count dogs in $events_count events!</h1>";

// Query to get the top ten dogs with the highest average scores
$query = "SELECT dogs.name AS dog_name, breeds.name AS breed_name, owners.name AS owner_name, owners.email, AVG(entries.score) AS avg_score 
          FROM dogs 
          INNER JOIN owners ON dogs.owner_id = owners.id 
          INNER JOIN entries ON dogs.id = entries.dog_id 
          INNER JOIN breeds ON dogs.breed_id = breeds.id 
          GROUP BY dogs.id, breeds.name, owners.name, owners.email 
          ORDER BY avg_score DESC LIMIT 10";

$result = $conn->query($query);

// Display the top ten dogs in a table
echo "<table border='1'>";
echo "<tr><th>Dog Name</th><th>Breed</th><th>Owner Name</th><th>Email</th><th>Average Score</th></tr>";
while ($row = $result->fetch_assoc()) {
    $dog_name = $row["dog_name"];
    $breed = $row["breed_name"];
    $owner_name = $row["owner_name"];
    $email = $row["email"];
    $avg_score = $row["avg_score"];

    // Display dog details in a table row
    echo "<tr>";
    echo "<td>$dog_name</td>";
    echo "<td>$breed</td>";
    echo "<td><a href='owner_details.php?owner=$owner_name'>$owner_name</a></td>";
    echo "<td><a href='mailto:$email'>$email</a></td>";
    echo "<td>$avg_score</td>";
    echo "</tr>";
}
echo "</table>";

// Close the connection
$conn->close();
?>
