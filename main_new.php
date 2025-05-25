<?php
session_start();
include __DIR__ . '/includes/db_connect.php';

// Get featured products
$featured_sql = "SELECT p.*, c.name as category_name 
                 FROM products p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE p.stock > 0 
                 ORDER BY p.id DESC 
                 LIMIT 8";
$featured_result = $conn->query($featured_sql);

// Get all categories
$categories_sql = "SELECT * FROM categories ORDER BY name";
$categories_result = $conn->query($categories_sql);

// Get special offers (products with stock > 0)
$offers_sql = "SELECT p.*, c.name as category_name 
               FROM products p 
               LEFT JOIN categories c ON p.category_id = c.id 
               WHERE p.stock > 0 
               ORDER BY p.price ASC 
               LIMIT 4";
$offers_result = $conn->query($offers_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Armora Mart</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 40px;
        }
        .category-card {
            transition: transform 0.3s;
            cursor: pointer;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .special-offer {
            background-color: #ff6b6b;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 mb-4">Welcome to Our Armora Mart</h1>
            <p class="lead mb-4">Fresh products, great prices, and excellent service</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="products.php" class="btn btn-primary btn-lg">Shop Now</a>
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="btn btn-outline-light btn-lg">Login</a>
                    <a href="register.php" class="btn btn-outline-light btn-lg">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Shop by Category</h2>
        <div class="row">
            <?php while($category = $categories_result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card category-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                            <h5 class="card-title"><?php echo $category['name']; ?></h5>
                            <p class="card-text"><?php echo $category['description']; ?></p>
                            <a href="products.php?category=<?php echo $category['id']; ?>" class="btn btn-outline-primary">Browse Products</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php while($product = $featured_result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['name']; ?></h5>
                            <p class="card-text">
                                <small class="text-muted"><?php echo $product['category_name']; ?></small><br>
                                <?php echo substr($product['description'], 0, 100) . '...'; ?>
                            </p>
                            <p class="card-text"><strong>$<?php echo number_format($product['price'], 2); ?></strong></p>
                            <div class="d-flex gap-2">
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary flex-grow-1">View Details</a>
                                <form action="cart.php" method="POST" class="flex-grow-1">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Special Offers Section -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Special Offers</h2>
        <div class="row">
            <?php while($offer = $offers_result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="special-offer">Special Offer!</div>
                        <img src="<?php echo $offer['image']; ?>" class="card-img-top" alt="<?php echo $offer['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $offer['name']; ?></h5>
                            <p class="card-text">
                                <small class="text-muted"><?php echo $offer['category_name']; ?></small><br>
                                <?php echo substr($offer['description'], 0, 100) . '...'; ?>
                            </p>
                            <p class="card-text">
                                <span class="text-decoration-line-through text-muted">$<?php echo number_format($offer['price'] * 1.2, 2); ?></span>
                                <strong class="text-danger">$<?php echo number_format($offer['price'], 2); ?></strong>
                            </p>
                            <div class="d-flex gap-2">
                                <a href="product.php?id=<?php echo $offer['id']; ?>" class="btn btn-outline-danger flex-grow-1">View Details</a>
                                <form action="cart.php" method="POST" class="flex-grow-1">
                                    <input type="hidden" name="product_id" value="<?php echo $offer['id']; ?>">
                                    <input type="hidden" name="action" value="add">
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <i class="fas fa-truck fa-3x mb-3 text-primary"></i>
                    <h4>Fast Delivery</h4>
                    <p>Free delivery on orders over $50</p>
                </div>
                <div class="col-md-4 mb-4">
                    <i class="fas fa-undo fa-3x mb-3 text-primary"></i>
                    <h4>Easy Returns</h4>
                    <p>30-day return policy</p>
                </div>
                <div class="col-md-4 mb-4">
                    <i class="fas fa-lock fa-3x mb-3 text-primary"></i>
                    <h4>Secure Payment</h4>
                    <p>100% secure payment</p>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>