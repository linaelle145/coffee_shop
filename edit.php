
<?php
// edit.php
include 'config.php';
include 'auth.php';

require_admin(); // Only admin can edit products

$id = $_GET['id'] ?? null;

// Load the product being edited
$stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    die("Product not found.");
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Update product
    $stmt = mysqli_prepare($conn,
        "UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");

    mysqli_stmt_bind_param($stmt, "sdsi", $name, $price, $image, $id);
    mysqli_stmt_execute($stmt);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">

                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Edit Product</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="">

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control"
                                       id="name" name="name"
                                       value="<?= htmlspecialchars($data['name']) ?>"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control"
                                       id="price" name="price"
                                       min="0.01" step="0.01"
                                       value="<?= htmlspecialchars($data['price']) ?>"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label for="image" class="form-label">Image URL</label>
                                <input type="url" class="form-control"
                                       id="image" name="image"
                                       value="<?= htmlspecialchars($data['image']) ?>"
                                       required>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary">
                                    Cancel
                                </a>

                                <button type="submit" name="update"
                                        class="btn btn-warning">
                                    Update Data
                                </button>
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
