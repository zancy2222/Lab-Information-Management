<?php
require 'config.php';

$email = $_POST['email'];
$password = $_POST['password'];  // Plain text password provided by user

// Sanitize email to avoid SQL injection
$email = $conn->real_escape_string($email);

// Check if user is Physician
$sql = "SELECT * FROM tblPhysician WHERE Email = '$email' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    // If no Physician found, check if the user is a Lab Technician
    $sql = "SELECT * FROM tblLabTechnician WHERE Email = '$email' LIMIT 1";
    $result = $conn->query($sql);
}

if ($result->num_rows > 0) {
    // User found, verify password
    $row = $result->fetch_assoc();

    // Check if the password matches the stored hashed password
    if (password_verify($password, $row['Password'])) {
        // Login successful
        if (isset($row['Specialty'])) {
            // User is a Physician, redirect to Physician dashboard
            $_SESSION['email'] = $email; // Store email in session
            header("Location: Physician/index.php");
        } else {
            // User is a Lab Technician, redirect to Lab Technician dashboard
            $_SESSION['email'] = $email; // Store email in session
            header("Location: Lab_Technician/index.php");
        }
        exit;
    } else {
        // Invalid password
        echo "Invalid login credentials.";
    }
} else {
    // No user found
    echo "Invalid login credentials.";
}

$conn->close();
?>
