<?php
require 'config.php';

// Fetch patients and physicians
$patientsResult = $conn->query("SELECT * FROM tblPatient");
$physiciansResult = $conn->query("SELECT * FROM tblPhysician");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Page</title>
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
                    <a class="nav-link active" href="laboratory.php"><i class="fas fa-flask"></i> Laboratory</a>
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

<!-- Laboratory Form -->
<div class="container">
    <div class="form-container">
        <h3 class="text-center mb-4">Register Test Order</h3>
        <form action="process_laboratory.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="patientID">Select Patient</label>
                        <select class="form-select" id="patientID" name="patientID" required>
                            <option value="" disabled selected>Select a patient</option>
                            <?php while ($row = $patientsResult->fetch_assoc()): ?>
                                <option value="<?= $row['PatientID'] ?>"><?= $row['FirstName'] ?> <?= $row['LastName'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="physicianID">Select Physician</label>
                        <select class="form-select" id="physicianID" name="physicianID" required>
                            <option value="" disabled selected>Select a physician</option>
                            <?php while ($row = $physiciansResult->fetch_assoc()): ?>
                                <option value="<?= $row['PhysicianID'] ?>"><?= $row['FirstName'] ?> <?= $row['LastName'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="testDetails">Test Details</label>
                <textarea class="form-control" id="testDetails" name="testDetails" rows="3" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="isLabTestRequired">Is Lab Test Required?</label>
                <select class="form-select" id="isLabTestRequired" name="isLabTestRequired" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- If lab test is required, show specimen collection form -->
            <div id="specimenCollection" style="display: none;">
                <div class="form-group mb-3">
                    <label for="specimenType">Specimen Type</label>
                    <input type="text" class="form-control" id="specimenType" name="specimenType" placeholder="Enter specimen type (e.g., Blood, Urine)" required>
                </div>
                <div class="form-group mb-3">
                    <label for="notes">Additional Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-custom w-100">Save Test Order</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Show or hide specimen collection based on lab test requirement
    document.getElementById('isLabTestRequired').addEventListener('change', function() {
        if (this.value == '1') {
            document.getElementById('specimenCollection').style.display = 'block';
        } else {
            document.getElementById('specimenCollection').style.display = 'none';
        }
    });
</script>
</body>
</html>
