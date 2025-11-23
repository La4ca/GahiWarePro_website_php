<?php
session_start();
require_once '../config/db.php';
require_once '../dao/crudDAO.php';

$errors = [];
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if ($identifier === '') $errors[] = "Username or email is required.";
    if ($password === '') $errors[] = "Password is required.";

    if (empty($errors)) {
        $dao = new crudDAO($pdo);
        $user = $dao->login($identifier, $password);

        if ($user !== false) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            header('Location: ../pages/dashboard.php');
            exit;
        } else {
            $errors[] = "Invalid username/email or password.";
        }
    }
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Login - GahiWare Store</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: 
                linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)),
                url('asset/img.jpg') repeat center center fixed;
            background-size: 500px 500px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 90%;
        }

        .logo {
            text-align: center;
            color: #e74c3c;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .tagline {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.5rem;
            box-sizing: border-box;
            border-radius: 6px;
            border: 2px solid #ddd;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #e74c3c;
        }

        .errors {
            background: #ffe6e6;
            border: 1px solid #ffcccc;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .errors ul {
            margin: 0;
            padding-left: 1rem;
        }

        button {
            width: 100%;
            margin-top: 1.5rem;
            padding: 0.75rem;
            border-radius: 6px;
            border: none;
            background: #e74c3c;
            font-weight: bold;
            cursor: pointer;
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }

        button:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .links a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .home-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .home-link a {
            color: #666;
            text-decoration: none;
        }

        .home-link a:hover {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="logo">GahiWare Store</div>
        <div class="tagline">Quality Hardware Solutions</div>
        
        <h1>Login to Your Account</h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="identifier">Username or Email</label>
            <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($identifier); ?>" required />

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />

            <button type="submit">Log In</button>
        </form>

        <div class="links">
            <a href="signup.php">Create an account</a>
            <a href="#">Forgot password?</a>
        </div>

        <div class="home-link">
            <a href="index.php">← Back to Store</a>
        </div>
    </div>
</body>
</html>
