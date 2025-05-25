<?php
session_start();
include __DIR__ . '/includes/db_connect.php';

// Hard code user role for testing
$_SESSION['user_role'] = 'customer'; // Set default role

// Get category filter
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Get all categories for the filter
$categories_sql = "SELECT * FROM categories ORDER BY name";
$categories_result = $conn->query($categories_sql);

// Get products with category filter
$products_sql = "SELECT p.*, c.name as category_name 
                 FROM products p 
                 JOIN categories c ON p.category_id = c.id";
if ($category_id > 0) {
    $products_sql .= " WHERE p.category_id = " . $category_id;
}
$products_sql .= " ORDER BY p.name";
$products_result = $conn->query($products_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Grocery Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <!-- Category Filter -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="products.php" class="list-group-item list-group-item-action <?php echo $category_id == 0 ? 'active' : ''; ?>">
                            All Products
                        </a>
                        <?php while($category = $categories_result->fetch_assoc()): ?>
                            <a href="products.php?category=<?php echo $category['id']; ?>" 
                               class="list-group-item list-group-item-action <?php echo $category_id == $category['id'] ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="col-md-9">
                <h2 class="mb-4">
                    <?php 
                    if ($category_id > 0) {
                        $category_name = $conn->query("SELECT name FROM categories WHERE id = " . $category_id)->fetch_assoc()['name'];
                        echo htmlspecialchars($category_name) . " Products";
                    } else {
                        echo "All Products";
                    }
                    ?>
                </h2>
                
                <div class="row">
                    <?php while($product = $products_result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if($product['image']): ?>
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                                    <p class="card-text">
                                        <strong>Price: $<?php echo number_format($product['price'], 2); ?></strong>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">Category: <?php echo htmlspecialchars($product['category_name']); ?></small>
                                    </p>
                                    <a href="view_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>