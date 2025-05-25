<?php
session_start();

// If user is already logged in, redirect to home
if(isset($_SESSION['user_id'])) {
    header("Location: main_new.php");
    exit();
}

// Check if we should show login form
$show_login = isset($_GET['show']) && $_GET['show'] === 'login';

// Get any messages
$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';

// Clear the messages
unset($_SESSION['success']);
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Grocery Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .welcome-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .btn-primary {
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
        }
        .store-logo {
            font-size: 2.5rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 2rem;
        }
        .switch-form {
            text-align: center;
            margin-top: 1rem;
        }
        .switch-form a {
            color: #007bff;
            text-decoration: none;
        }
        .switch-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="welcome-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="store-logo">
                    <i class="fas fa-shopping-basket"></i>Armora Mart
                </div>
                <h2 class="mb-4">Welcome to Our Online Armora Mart</h2>
                <p class="lead"><?php echo $show_login ? 'Login to your account' : 'Create your account to get started'; ?></p>
                
                <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($success_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <?php if ($show_login): ?>
                    <!-- Login Form -->
                    <div class="card">
                        <div class="card-header text-center">
                            <h3 class="mb-0">Login</h3>
                        </div>
                        <div class="card-body p-4">
                            <form action="login.php" method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                            <div class="switch-form">
                                <p>Don't have an account? <a href="landing.php">Register here</a></p>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Registration Form -->
                    <div class="card">
                        <div class="card-header text-center">
                            <h3 class="mb-0">Register</h3>
                        </div>
                        <div class="card-body p-4">
                            <form action="register.php" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reg_email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="reg_email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reg_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="reg_password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Register</button>
                            </form>
                            <div class="switch-form">
                                <p>Already have an account? <a href="landing.php?show=login">Login here</a></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 