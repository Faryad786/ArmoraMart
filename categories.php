<?php
session_start();
require_once 'includes/db_connect.php';

// Get all categories with their products
$stmt = $conn->prepare("
    SELECT c.*, 
           COUNT(p.id) as product_count,
           GROUP_CONCAT(p.id) as product_ids
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    GROUP BY c.id
    ORDER BY c.name
");
$stmt->execute();
$categories = $stmt->get_result();

// Get selected category if any
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : null;

// If category is selected, get its products
if ($selected_category) {
    $stmt = $conn->prepare("
        SELECT p.*, c.name as category_name
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.category_id = ?
        ORDER BY p.name
    ");
    $stmt->bind_param("i", $selected_category);
    $stmt->execute();
    $products = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Grocery Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .category-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .product-card {
            height: 100%;
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .category-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #007bff;
        }
        .product-count {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <h1 class="mb-4">Categories</h1>

        <?php if (!$selected_category): ?>
            <!-- Show all categories -->
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card h-100 category-card" onclick="window.location.href='categories.php?category=<?php echo $category['id']; ?>'">
                            <div class="card-body text-center">
                                <i class="fas fa-shopping-basket category-icon"></i>
                                <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($category['description']); ?></p>
                                <span class="badge bg-primary"><?php echo $category['product_count']; ?> Products</span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <!-- Show products of selected category -->
            <?php 
            $category_name = '';
            $categories->data_seek(0);
            while ($cat = $categories->fetch_assoc()) {
                if ($cat['id'] == $selected_category) {
                    $category_name = $cat['name'];
                    break;
                }
            }
            ?>
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="categories.php">Categories</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($category_name); ?></li>
                </ol>
            </nav>

            <div class="row row-cols-1 row-cols-md-4 g-4">
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="col">
                        <div class="card h-100 product-card">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                 class="card-img-top product-image" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text text-truncate"><?php echo htmlspecialchars($product['description']); ?></p>
                                <p class="card-text text-primary fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                                <a href="view_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>