<?php
require 'config.php';

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$gender = $_POST['gender'];
$birthDate = $_POST['birthDate'];
$contact = $_POST['contact'];
$address = $_POST['address'];

$sql = "INSERT INTO tblPatient (FirstName, LastName, Gender, BirthDate, ContactNumber, Address) 
        VALUES ('$firstName', '$lastName', '$gender', '$birthDate', '$contact', '$address')";

if ($conn->query($sql) === TRUE) {
    echo "Patient added successfully.";
    header("Location: index.php"); // Redirect to the Physician Dashboard
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
