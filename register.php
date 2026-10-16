<?php
// register.php
include 'config.php';
session_start();

$error = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? '';

    // Check if username already exists
    $stmt = mysqli_prepare($conn,
        "SELECT id FROM users WHERE username = ?");

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    // Check role
    if ($role !== 'customer') {
        $error = "Role forbidden. You cannot register as an admin.";

    } elseif ($username === '' || $password === '') {
        $error = "Username and password are required.";

    } elseif (mysqli_num_rows($result) > 0) {
        $error = "Username already exists.";

    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create customer account
        $stmt = mysqli_prepare($conn,
            "INSERT INTO users (username, password, role)
             VALUES (?, ?, ?)");

        mysqli_stmt_bind_param($stmt, "sss",
            $username, $hashed_password, $role);

        mysqli_stmt_execute($stmt);

        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">

                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Coffee Shop Registration</h5>
                    </div>

                    <div class="card-body">

                        <!-- Error message -->
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    Username
                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="username"
                                       name="username"
                                       required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password
                                </label>

                                <input type="password"
                                       class="form-control"
                                       id="password"
                                       name="password"
                                       required>
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    Role
                                </label>

                                <select class="form-select"
                                        id="role"
                                        name="role"
                                        required>

                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>

                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="d-grid gap-2">

                                <button type="submit"
                                        name="register"
                                        class="btn btn-success">
                                    Register
                                </button>

                                <a href="login.php"
                                   class="btn btn-secondary">
                                    Already have an account? Login
                                </a>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
