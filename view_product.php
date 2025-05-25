<?php
session_start();
require_once 'includes/db_connect.php';

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];

// Get product details with more information
$stmt = $conn->prepare("
    SELECT p.*, c.name as category_name, c.description as category_description
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ?
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: products.php");
    exit();
}

$product = $result->fetch_assoc();

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please login to add items to cart";
        header("Location: landing.php?show=login");
        exit();
    }

    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // Add or update item in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image' => $product['image']
        ];
    }
    
    $_SESSION['success'] = "Product added to cart successfully!";
    header("Location: view_product.php?id=" . $product_id);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Grocery Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .product-details {
            padding: 20px;
        }
        .price {
            font-size: 1.5rem;
            color: #28a745;
            font-weight: bold;
        }
        .quantity-input {
            width: 100px;
        }
        .category-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            margin-bottom: 10px;
        }
        .details-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .details-section h5 {
            color: #007bff;
            margin-bottom: 15px;
        }
        .details-list li {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .details-list li:last-child {
            border-bottom: none;
        }
        .stock-status {
            font-weight: bold;
        }
        .in-stock {
            color: #28a745;
        }
        .low-stock {
            color: #ffc107;
        }
        .out-of-stock {
            color: #dc3545;
        }
        .product-features {
            margin-top: 20px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .feature-item i {
            margin-right: 10px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container my-5">
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     class="product-image">
            </div>
            <div class="col-md-6 product-details">
                <span class="badge bg-primary category-badge">
                    <?php echo htmlspecialchars($product['category_name']); ?>
                </span>
                <h1 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="price mb-4">$<?php echo number_format($product['price'], 2); ?></p>
                
                <div class="mb-4">
                    <h5>Description</h5>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <form method="POST" action="" class="mb-4">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <label for="quantity" class="form-label">Quantity:</label>
                                <input type="number" 
                                       class="form-control quantity-input" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="99">
                            </div>
                            <div class="col-auto">
                                <button type="submit" 
                                        name="add_to_cart" 
                                        class="btn btn-primary btn-lg">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info">
                        Please <a href="landing.php?show=login">login</a> to add items to your cart.
                    </div>
                <?php endif; ?>

                <div class="details-section">
                    <h5><i class="fas fa-info-circle"></i> Product Details</h5>
                    <ul class="list-unstyled details-list">
                        <li>
                            <strong>Stock Status:</strong>
                            <?php
                            $stock_status = '';
                            if ($product['stock'] > 10) {
                                $stock_status = '<span class="stock-status in-stock">In Stock</span>';
                            } elseif ($product['stock'] > 0) {
                                $stock_status = '<span class="stock-status low-stock">Low Stock</span>';
                            } else {
                                $stock_status = '<span class="stock-status out-of-stock">Out of Stock</span>';
                            }
                            echo $stock_status . ' (' . $product['stock'] . ' units available)';
                            ?>
                        </li>
                        <li><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></li>
                        <?php if($product['weight']): ?>
                            <li><strong>Weight:</strong> <?php echo htmlspecialchars($product['weight']); ?></li>
                        <?php endif; ?>
                        <li><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></li>
                        <?php if($product['category_description']): ?>
                            <li><strong>Category Description:</strong> <?php echo htmlspecialchars($product['category_description']); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="product-features">
                    <h5><i class="fas fa-star"></i> Features</h5>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>High Quality Product</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-truck"></i>
                        <span>Fast Delivery Available</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-undo"></i>
                        <span>Easy Returns</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure Shopping</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 