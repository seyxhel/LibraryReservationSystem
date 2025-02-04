<?php
// Database connection parameters
$serverName = "WIN-DRJ0QUA8KCP\SQLEXPRESS"; // or your server name
$connectionOptions = array(
    "Database" => "NewReservationSystem", // replace with your database name
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
