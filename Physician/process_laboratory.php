<?php
require 'config.php';  // Ensure you have the database connection in this file.

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $patientID = $_POST['patientID'];
    $physicianID = $_POST['physicianID'];
    $testDetails = $_POST['testDetails'];
    $isLabTestRequired = $_POST['isLabTestRequired'];

    // Start a transaction to ensure both test order and specimen collection (if needed) are processed atomically
    $conn->begin_transaction();

    try {
        // Insert the test order into tblTestOrder
        $stmt = $conn->prepare("INSERT INTO tblTestOrder (PatientID, PhysicianID, TestDetails, IsLabTestRequired) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $patientID, $physicianID, $testDetails, $isLabTestRequired);
        $stmt->execute();

        // Get the last inserted OrderID
        $orderID = $stmt->insert_id;

        // If lab test is required, insert specimen collection details
        if ($isLabTestRequired == 1) {
            $specimenType = $_POST['specimenType'];
            $notes = $_POST['notes'];

            // Insert the specimen collection record
            $stmt = $conn->prepare("INSERT INTO tblSpecimenCollect (OrderID, SpecimenType, Notes) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $orderID, $specimenType, $notes);
            $stmt->execute();
        }

        // Commit the transaction
        $conn->commit();

        // Redirect to the laboratory page with a success message
        header("Location: laboratory.php?success=Test Order Registered Successfully");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction if something went wrong
        $conn->rollback();

        // Redirect to the laboratory page with an error message
        header("Location: laboratory.php?error=" . urlencode("Error: " . $e->getMessage()));
        exit;
    }
} else {
    // If the form is not submitted via POST, redirect to the laboratory page
    header("Location: laboratory.php");
    exit;
}
?>
