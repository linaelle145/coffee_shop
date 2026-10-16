<?php
// navbar.php
?>

<nav class="navbar navbar-sdark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="index.php">Coffee Shop</a>

        <div class="d-flex align-items-center gap-2">

            <?php if (is_admin()): ?>
                <a href="users.php" class="btn btn-outline-light btn-sm">
                    Users
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>

                <!-- Logged-in user -->
                <span class="text-white small">
                    <?= htmlspecialchars($_SESSION['username'] ?? '') ?>

                    <span class="badge bg-secondary">
                        <?= htmlspecialchars($_SESSION['role'] ?? '') ?>
                    </span>
                </span>

                <a href="logout.php" class="btn btn-danger btn-sm">
                    Logout
                </a>

            <?php else: ?>

                <!-- Guest -->
                <span class="text-white small">Guest</span>

                <a href="login.php" class="btn btn-primary btn-sm">
                    Login
                </a>

                <a href="register.php" class="btn btn-success btn-sm">
                    Register
                </a>

            <?php endif; ?>

        </div>
    </div>
</nav>
