<?php
session_start();
require_once '../config/db.php';
require_once '../dao/crudDAO.php';

$dao = new crudDAO($pdo);
$products = $dao->getAllProducts();
$categories = ['Tools', 'Electrical', 'Plumbing', 'Hardware', 'Paints', 'Safety'];

// Handle search
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $products = $dao->searchProducts($searchTerm);
}

// Filter by category
if (isset($_GET['category']) && $_GET['category'] != 'all') {
    $products = $dao->getProductsByCategory($_GET['category']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GahiWarePro Hardware</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #e74c3c;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #e74c3c;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #e74c3c;
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #e74c3c;
            color: #e74c3c;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('asset/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 4rem 1rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Search and Filter */
        .search-filter {
            background: white;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 1200px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .search-box {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .categories {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
        }

        .category-btn {
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border: 2px solid #ddd;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .category-btn.active,
        .category-btn:hover {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
        }

        /* Products Grid */
        .products-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 2rem;
            color: #2c3e50;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #ddd;
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .product-description {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 1rem;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #666;
        }

        .product-category {
            background: #e74c3c;
            color: white;
            padding: 0.2rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem 1rem;
            margin-top: 4rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="logo">GahiWarePro</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="admin/dashboard.php" class="btn btn-primary">Dashboard</a>
                    <a href="logout.php" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline">Login</a>
                    <a href="signup.php" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <h1>Quality Hardware Solutions</h1>
        <p>Your one-stop shop for all hardware needs. Browse our extensive catalog of tools, materials, and equipment.</p>
        <a href="#products" class="btn btn-primary" style="margin-right: 1rem;">Shop Now</a>
        <a href="#categories" class="btn btn-outline">View Categories</a>
    </section>

    <!-- Search and Filter -->
    <section class="search-filter">
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search for products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if (isset($_GET['search']) || isset($_GET['category'])): ?>
                <a href="index.php" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </form>

        <div class="categories" id="categories">
            <a href="index.php" class="category-btn <?php echo !isset($_GET['category']) || $_GET['category'] == 'all' ? 'active' : ''; ?>">All Products</a>
            <?php foreach ($categories as $category): ?>
                <a href="index.php?category=<?php echo urlencode($category); ?>" class="category-btn <?php echo (isset($_GET['category']) && $_GET['category'] == $category) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($category); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <h2 class="section-title">Our Products</h2>
        
        <?php if (empty($products)): ?>
            <div style="text-align: center; padding: 3rem;">
                <h3>No products found</h3>
                <p>Try adjusting your search or filter criteria.</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php echo $product['image'] ? '<img src="'.$product['image'].'" alt="'.$product['name'].'" style="width:100%;height:100%;object-fit:cover;">' : '🛠️'; ?>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="product-price">₱<?php echo number_format($product['price'], 2); ?></div>
                            <div class="product-meta">
                                <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                                <span>Stock: <?php echo $product['stock']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 GahiWarePro Hardware. All rights reserved.</p>
        <p>Your trusted partner in quality hardware solutions</p>
    </footer>
</body>
</html>
