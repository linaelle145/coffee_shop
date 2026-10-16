
<?php
// index.php

include 'auth.php';
include 'config.php';

// Save order (Customer and Guest only)
if (isset($_POST['order']) && !is_admin()) {

    $product_id = (int) ($_POST['product_id'] ?? 0);

    // Get product price from database
    $stmt = mysqli_prepare($conn,
        "SELECT price FROM products WHERE id = ?");

    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);

    $result_product = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result_product);

    if ($product) {

        $price = $product['price'];
        $user_id = $_SESSION['user_id'] ?? null;

        // Apply 10% discount for logged-in customers
        if (isset($_SESSION['user_id']) &&
            ($_SESSION['role'] ?? '') === 'customer') {

            $price = $price * 0.90;
        }

        // Insert order into database
        $stmt = mysqli_prepare($conn,
            "INSERT INTO orders (product_id, user_id, price)
             VALUES (?, ?, ?)");

        mysqli_stmt_bind_param($stmt, "iid",
            $product_id, $user_id, $price);

        mysqli_stmt_execute($stmt);

        header("Location: index.php?ordered=1");
        exit;
    }
}

// Get all products
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">

        <!-- Order confirmation -->
        <?php if (isset($_GET['ordered'])): ?>
            <div class="alert alert-success">
                Order placed successfully!
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Coffee Shop</h4>

                <?php if (is_admin()): ?>
                    <a href="create.php" class="btn btn-success btn-sm">
                        + Add Product
                    </a>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>

                                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td>
                                            <img src="<?= htmlspecialchars($row['image']) ?>" width="80">
                                        </td>

                                        <td><?= htmlspecialchars($row['name']) ?></td>

                                        <!-- Price with customer discount -->
                                        <td>
                                            <?php if (isset($_SESSION['user_id']) &&
                                                      ($_SESSION['role'] ?? '') === 'customer'): ?>

                                                <!-- Original price -->
                                                <del class="text-muted">
                                                    <?= number_format($row['price'], 0, ',', ' ') ?> IDR
                                                </del>

                                                <br>

                                                <!-- Discounted price -->
                                                <span class="text-success fw-bold">
                                                    <?= number_format($row['price'] * 0.90, 0, ',', ' ') ?> IDR
                                                </span>

                                            <?php else: ?>

                                                <?= number_format($row['price'], 0, ',', ' ') ?> IDR

                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">

                                            <!-- Customer and Guest only -->
                                            <?php if (!is_admin()): ?>

                                                <form method="POST" class="d-inline">
                                                    <input type="hidden"
                                                           name="product_id"
                                                           value="<?= $row['id'] ?>">

                                                    <button type="submit"
                                                            name="order"
                                                            class="btn btn-primary btn-sm">
                                                        Order
                                                    </button>
                                                </form>

                                            <?php endif; ?>

                                            <!-- Admin only -->
                                            <?php if (is_admin()): ?>

                                                <a href="edit.php?id=<?= $row['id'] ?>"
                                                   class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>

                                                <a href="delete.php?id=<?= $row['id'] ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                                    Delete
                                                </a>

                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        No products found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
