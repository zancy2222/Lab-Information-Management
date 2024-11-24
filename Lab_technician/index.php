<?php
require 'config.php';

// Fetch all test orders that need results from the database
$testOrdersResult = $conn->query("SELECT * FROM tblTestOrder WHERE LabTestGenerated = 0");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data to generate the result
    $orderID = $_POST['orderID'];
    $testResult = $_POST['testResult'];
    $billAmount = $_POST['billAmount'];

    // Start a transaction to ensure the test result and bill are updated correctly
    $conn->begin_transaction();

    try {
        // Insert test result into tblResult
        $stmt = $conn->prepare("INSERT INTO tblResult (SpecimenID, TechnicianID, ResultDetails) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $specimenID, $technicianID, $testResult);
        $stmt->execute();

        // Update the tblTestOrder to mark the lab test as generated
        $stmt = $conn->prepare("UPDATE tblTestOrder SET LabTestGenerated = 1 WHERE OrderID = ?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();

        // Insert into tblBilling using correct column name TotalAmount
        $stmt = $conn->prepare("INSERT INTO tblBilling (OrderID, TechnicianID, TotalAmount, PaymentStatus) VALUES (?, ?, ?, ?)");
        $paymentStatus = 'Pending'; // Default payment status
        $stmt->bind_param("iids", $orderID, $technicianID, $billAmount, $paymentStatus);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect to the technician page with a success message
        header("Location: index.php?success=Results and Bill Generated Successfully");
        exit;
    } catch (Exception $e) {
        // Rollback the transaction if something went wrong
        $conn->rollback();

        // Redirect to the technician page with an error message
        header("Location: index.php?error=" . urlencode("Error: " . $e->getMessage()));
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Lab Test Results and Bills</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            background-color: #0056b3;
            border-radius: 5px;
        }

        .container {
            margin-top: 30px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
        }

        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Lab Technician Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php"><i class="fas fa-flask"></i> Generate Results & Bill</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Generate Results Form -->
<div class="container">
    <div class="form-container">
        <h3 class="text-center mb-4">Generate Lab Test Results and Bills</h3>
        <form action="index.php" method="POST">
            <div class="mb-3">
                <label for="orderID" class="form-label">Select Test Order</label>
                <select class="form-select" id="orderID" name="orderID" required>
                    <option value="" disabled selected>Select a Test Order</option>
                    <?php while ($row = $testOrdersResult->fetch_assoc()): ?>
                        <option value="<?= $row['OrderID'] ?>">Order #<?= $row['OrderID'] ?> - Patient: <?= $row['PatientID'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="testResult" class="form-label">Test Result</label>
                <textarea class="form-control" id="testResult" name="testResult" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="billAmount" class="form-label">Bill Amount</label>
                <input type="number" class="form-control" id="billAmount" name="billAmount" required>
            </div>

            <button type="submit" class="btn btn-custom w-100">Generate Result and Bill</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
