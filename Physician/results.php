<?php
// Include the database connection file
require 'config.php';

// Fetch results by joining tblTestOrder, tblPatient, tblPhysician, tblBilling, tblSpecimenCollect, and tblResult
$sql = "
    SELECT 
        t.OrderID,
        t.TestDetails,
        t.PatientID,
        p.FirstName AS PatientFirstName,
        p.LastName AS PatientLastName,
        t.PhysicianID,
        ph.FirstName AS PhysicianFirstName,
        ph.LastName AS PhysicianLastName,
        b.TotalAmount,
        b.BillingDate,
        r.ResultDate,
        r.ResultDetails
    FROM tblTestOrder t
    JOIN tblPatient p ON t.PatientID = p.PatientID
    JOIN tblPhysician ph ON t.PhysicianID = ph.PhysicianID
    JOIN tblBilling b ON t.OrderID = b.OrderID
    LEFT JOIN tblSpecimenCollect sc ON t.OrderID = sc.OrderID
    LEFT JOIN tblResult r ON sc.SpecimenID = r.SpecimenID
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Physician Dashboard - Results</title>
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

        .table {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
        }

        .btn-custom:hover {
            background-color: #218838;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar-toggler-icon {
            color: white;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Physician Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-user-plus"></i> Add Patient</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="laboratory.php"><i class="fas fa-flask"></i> Laboratory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="results.php"><i class="fas fa-file-medical"></i> Results</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Results Table -->
<div class="container mt-4">
    <h2>Test Results and Billing Information</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Patient Name</th>
                    <th>Physician Name</th>
                    <th>Test Details</th>
                    <th>Total Amount</th>
                    <th>Billing Date</th>
                    <!-- <th>Result Date</th>
                    <th>Result Details</th> -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['OrderID'] ?></td>
                        <td><?= $row['PatientFirstName'] ?> <?= $row['PatientLastName'] ?></td>
                        <td><?= $row['PhysicianFirstName'] ?> <?= $row['PhysicianLastName'] ?></td>
                        <td><?= $row['TestDetails'] ?></td>
                        <td><?= number_format($row['TotalAmount'], 2) ?></td>
                        <td><?= date('Y-m-d H:i:s', strtotime($row['BillingDate'])) ?></td>
                        <!-- <td><?= $row['ResultDate'] ? date('Y-m-d H:i:s', strtotime($row['ResultDate'])) : 'N/A' ?></td>
                        <td><?= $row['ResultDetails'] ? $row['ResultDetails'] : 'N/A' ?></td> -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No results found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
