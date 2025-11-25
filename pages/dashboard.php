<?php
session_start();
require_once '../config/db.php';
require_once '../dao/crudDAO.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$dao = new crudDAO($pdo);
$user = $_SESSION['user'];

// Get user-specific data
$recentProducts = $dao->getAllProducts();
$cartItems = $dao->getCartItems($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GahiWarePro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #fdf2f8;
            color: #333;
        }

        :root {
            --primary-pink: #ec4899;
            --dark-pink: #db2777;
            --light-pink: #fce7f3;
            --accent-purple: #8b5cf6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-purple));
            color: white;
            padding: 2rem 1rem;
        }

        .sidebar h2 {
            margin-bottom: 2rem;
            text-align: center;
        }

        .sidebar-nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 0.75rem 1rem;
            margin: 0.5rem 0;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.2);
        }

        .main-content {
            flex: 1;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .welcome-message h1 {
            color: var(--dark-pink);
            margin-bottom: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            color: var(--primary-pink);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .recent-products {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .btn {
            padding: 0.5rem 1rem;
            background: var(--primary-pink);
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }

        .btn:hover {
            background: var(--dark-pink);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>GahiWarePro</h2>
            <nav class="sidebar-nav">
                <a href="dashboard.php">Dashboard</a>
                <a href="store.php">Store</a>
                <a href="cart.php">My Cart</a>
                <a href="orders.php">My Orders</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="welcome-message">
                    <h1>Welcome back, <?php echo htmlspecialchars($user['firstname']); ?>!</h1>
                    <p>Ready to find your next hardware solution?</p>
                </div>
                <a href="store.php" class="btn">Go to Store</a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo count($cartItems); ?></h3>
                    <p>Items in Cart</p>
                </div>
                <div class="stat-card">
                    <h3>₱0.00</h3>
                    <p>Total Spent</p>
                </div>
                <div class="stat-card">
                    <h3>0</h3>
                    <p>Orders</p>
                </div>
            </div>

            <div class="recent-products">
                <h2>Recent Products</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <?php foreach (array_slice($recentProducts, 0, 4) as $product): ?>
                        <div style="border: 1px solid var(--light-pink); padding: 1rem; border-radius: 8px;">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <p>₱<?php echo number_format($product['price'], 2); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>