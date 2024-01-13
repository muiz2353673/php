<?php
// Display errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$owners_count = 0;
$dogs_count = 0;
$events_count = 0;

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
$queryCounts = "
    SELECT 
        (SELECT COUNT(DISTINCT id) FROM owners) AS owners_count,
        (SELECT COUNT(DISTINCT id) FROM dogs) AS dogs_count,
        (SELECT COUNT(DISTINCT id) FROM events) AS events_count
";

$resultCounts = $conn->query($queryCounts);

// Check if the query was successful
if ($resultCounts === false) {
    die("Query failed: " . $conn->error);
}

// Check if there are rows in the result set
if ($resultCounts->num_rows > 0) {
    $rowCounts = $resultCounts->fetch_assoc();
    $owners_count = $rowCounts["owners_count"];
    $dogs_count = $rowCounts["dogs_count"];
    $events_count = $rowCounts["events_count"];
}

// Display the header
echo "<h1>Welcome to Poppleton Dog Show! This year $owners_count owners entered $dogs_count dogs in $events_count events!</h1>";

// Query to get the top ten dogs with the highest average scores
$topTenDogsQuery = 
"SELECT dogs.id AS dog_id, dogs.name AS dog_name, breeds.name AS breed_name, owners.name AS owner_name, owners.email, AVG(entries.score) AS avg_score
FROM dogs
INNER JOIN breeds ON dogs.breed_id = breeds.id
INNER JOIN owners ON dogs.owner_id = owners.id
INNER JOIN entries ON dogs.id = entries.dog_id
GROUP BY dog_id, dog_name, breed_name, owner_name, owners.email
HAVING COUNT(entries.competition_id) > 1
ORDER BY avg_score DESC
LIMIT 10"
;

$resultTopTenDogs = $conn->query($topTenDogsQuery);

// Check if the query was successful
if ($resultTopTenDogs === false) {
    die("Query failed: " . $conn->error);
}

// Display the top ten dogs in a table
echo "<table border='1'>";
echo "<tr><th>Dog Name</th><th>Breed</th><th>Owner Name</th><th>Email</th><th>Average Score</th></tr>";
while ($row = $resultTopTenDogs->fetch_assoc()) {
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
