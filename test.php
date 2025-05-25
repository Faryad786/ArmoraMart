<?php
session_start();
include __DIR__ . '/includes/db_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>Test Page</h1>
    <?php include __DIR__ . '/includes/header.php'; ?>
    <p>This is a test page.</p>
    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html> 