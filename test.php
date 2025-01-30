<?php
// Database connection parameters
$serverName = "DESKTOP-H98F7H6\SQLEXPRESS"; // or your server name
$connectionOptions = array(
    "Database" => "NewResrvationSystem", // replace with your database name
    "Uid" => "", // replace with your username
    "PWD" => ""  // replace with your password
);

// Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check connection
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true)); // Show errors if connection fails
}

echo "Connected to SQL Server successfully!";

// Close the connection
sqlsrv_close($conn);
?>
