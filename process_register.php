<?php
require 'config.php';

$role = $_POST['role'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password

// Insert user data including the hashed password
if ($role === 'Physician') {
    $specialty = $_POST['specialty'];
    $sql = "INSERT INTO tblPhysician (FirstName, LastName, Specialty, ContactNumber, Email, Password) 
            VALUES ('$firstName', '$lastName', '$specialty', '$contact', '$email', '$password')";
} else {
    $dateHired = $_POST['dateHired'];
    $sql = "INSERT INTO tblLabTechnician (FirstName, LastName, ContactNumber, Email, DateHired, Password) 
            VALUES ('$firstName', '$lastName', '$contact', '$email', '$dateHired', '$password')";
}

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
