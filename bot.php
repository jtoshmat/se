#!/bin/php
<?php
//Get console argument
var_dump($argv[1]);


//Connect to mysl


$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "main";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id,email,first_name FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    $output = [];
    while($row = $result->fetch_assoc()) {
	$id = $row["id"]; 
        $output[$id]['first_name'] = $row['first_name'];
        $output[$id]['email'] = $row['email'];
    }
} else {
    echo "0 results";
}

var_dump($output);

$conn->close();

?>
