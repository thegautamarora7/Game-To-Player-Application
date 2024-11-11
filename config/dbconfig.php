<?php

// Database connection parameters
$hostname = 'localhost';    // Hostname of the MySQL server (typically 'localhost' for local development)
$username = 'root';         // Database username (default is 'root' for local development)
$password = '';             // Password for the database (empty by default for local development)
$databaseName = 'G2P_Group6'; // The name of the database to connect to

// Establishing connection to the database
$connectionObject = mysqli_connect($hostname, $username, $password, $databaseName);

// Check if the connection was successful
if (!$connectionObject) {
    // If connection fails, display an error message
    echo "Connection Failed";
}

?>
