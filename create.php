<?php
// create.php
include 'config.php';
include 'auth.php';
require_admin(); // only an administrator should be able to add products 

/*// Load the majors for the dropdown
$majors = mysqli_query($conn, "SELECT id, name FROM majors ORDER BY name");*/

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Insert into database
    $stmt = mysqli_prepare($conn,
        "INSERT INTO products (name, price, image) VALUES (?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "sds", $name, $price, $image);
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
    <title>Add Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Add New Product</h5>
                    </div>
                    <div class="card-body">
                        <form name="ProductForm" method="POST" action="">
                            <div class="mb-3">
                                 <label for="price" class="form-label">Price</label>
                                  <input type="number" class="form-control" id="price" name="price" min="0.01" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-4">
                                <label for="image" class="form-label">Image</label>
                                <input type="text" class="form-control" id="image" name="image" placeholder="Image URL" required>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-success">Save Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
    <script>
        function validateForm() {
            let nim = document.forms["studentForm"]["nim"].value;
            let nimError = document.getElementById("nimError");
            let nimInput = document.getElementById("nim");
            if (isNaN(nim) || nim.trim() === "") {
                nimError.style.display = "block";
                nimInput.classList.add("is-invalid");
                return false;
            }
            nimError.style.display = "none";
            nimInput.classList.remove("is-invalid");
            return true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>