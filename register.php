<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Register</h2>
    <form action="process_register.php" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="" selected disabled>Select your role</option>
                <option value="Physician">Physician</option>
                <option value="Lab Technician">Lab Technician</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="firstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter your first name" required>
        </div>
        <div class="mb-3">
            <label for="lastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label for="contact" class="form-label">Contact Number</label>
            <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter your contact number" required>
        </div>
        <div class="mb-3" id="specialtyGroup">
            <label for="specialty" class="form-label">Specialty</label>
            <input type="text" class="form-control" id="specialty" name="specialty" placeholder="Enter your specialty">
        </div>
        <div class="mb-3" id="dateHiredGroup" style="display: none;">
            <label for="dateHired" class="form-label">Date Hired</label>
            <input type="date" class="form-control" id="dateHired" name="dateHired">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <p class="text-center mt-3">Already have an account? <a href="index.php">Login here</a>.</p>
</div>

<script>
    // Show/Hide fields based on role
    const role = document.getElementById('role');
    const specialtyGroup = document.getElementById('specialtyGroup');
    const dateHiredGroup = document.getElementById('dateHiredGroup');

    role.addEventListener('change', () => {
        if (role.value === 'Physician') {
            specialtyGroup.style.display = 'block';
            dateHiredGroup.style.display = 'none';
        } else {
            specialtyGroup.style.display = 'none';
            dateHiredGroup.style.display = 'block';
        }
    });
</script>
</body>
</html>
