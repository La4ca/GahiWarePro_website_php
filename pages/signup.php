<?php
require_once '../config/db.php';
require_once '../dao/crudDAO.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastname        = trim($_POST['lastname'] ?? '');
    $firstname       = trim($_POST['firstname'] ?? '');
    $username        = trim($_POST['username'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirm_pass    = $_POST['confirm_password'] ?? '';
    $email           = trim($_POST['email'] ?? '');

    // Basic validation
    if ($lastname === '') $errors[] = "Last name is required.";
    if ($firstname === '') $errors[] = "First name is required.";

    if ($username === '') {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
        $errors[] = "Username must be 3-20 characters; letters, numbers, underscore only.";
    }

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm_pass) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    // If validation passes, create user
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $dao = new crudDAO($pdo);
        $result = $dao->create($lastname, $firstname, $username, $password_hash, $email);

        if ($result === true) {
            $success = true;
        } else {
            if (strpos($result, 'SQLSTATE[23000]') !== false) {
                if (strpos($result, 'username') !== false) {
                    $errors[] = "Username already taken.";
                } elseif (strpos($result, 'email') !== false) {
                    $errors[] = "Email already registered.";
                } else {
                    $errors[] = "Duplicate entry. Please check your input.";
                }
            } else {
                $errors[] = "Database error: " . $result;
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sign Up - GahiWare Store</title>
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
            max-width: 450px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
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
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-group {
            flex: 1;
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
            color: #555;
            font-size: 0.9rem;
        }

        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.5rem;
            box-sizing: border-box;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus, input[type="email"]:focus {
            outline: none;
            border-color: #e74c3c;
        }

        .errors {
            background: #ffe6e6;
            border: 1px solid #ffcccc;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 6px;
        }

        .errors ul {
            margin: 0;
            padding-left: 1rem;
        }

        .success {
            background: #e6ffe6;
            border: 1px solid #ccffcc;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
            color: #2d6a4f;
        }

        button {
            display: block;
            width: 100%;
            margin-top: 1.5rem;
            padding: 0.75rem;
            font-weight: bold;
            background-color: #e74c3c;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }

        button:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .links {
            text-align: center;
            margin-top: 1.5rem;
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

        @media (max-width: 480px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="logo">GahiWare Store</div>
        <div class="tagline">Join our hardware community</div>
        
        <h1>Create Your Account</h1>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">
                🎉 Registration successful!<br>
                You may now <a href="login.php" style="color: #e74c3c;">login</a> to your account.
            </div>
        <?php else: ?>
            <form method="post" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname ?? ''); ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname ?? ''); ?>" required />
                    </div>
                </div>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required />

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />

                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required />

                <button type="submit">Create Account</button>
            </form>
        <?php endif; ?>

        <div class="links">
            <a href="login.php">Already have an account? Login here</a>
        </div>

        <div class="home-link">
            <a href="index.php">← Back to Store</a>
        </div>
    </div>
</body>
</html>