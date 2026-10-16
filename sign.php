<?php
session_start();
require_once 'config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Find user by email
            $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                // Store user session and redirect
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
                header('Location: welcome.php');
                exit();
            } else {
                $message = '<div class="alert alert-danger mt-3 py-2" role="alert"><small>No account found with that email.</small></div>';
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $message = '<div class="alert alert-danger mt-3 py-2" role="alert"><small>An error occurred. Please try again.</small></div>';
        }
    } else {
        $message = '<div class="alert alert-warning mt-3 py-2" role="alert"><small>Please enter a valid email address.</small></div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 2rem; 
            width: 100%;
            max-width: 380px; 
            margin: auto;
        }
        .login-title {
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 0.25rem;
            text-align: center;
            font-size: 1.5rem; 
        }
        .login-subtitle {
            font-size: 0.85rem; 
            color: #718096;
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }
        .form-control {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background-color: #ffffff;
        }
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        .btn-primary {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(118, 75, 162, 0.4);
            background: linear-gradient(to right, #764ba2, #667eea);
        }
        .link-text {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        .link-text:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-card">
        <h2 class="login-title">Welcome Back!</h2>
        <p class="login-subtitle">Sign in to access your account dashboard.</p>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label text-muted fw-semibold">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted" style="font-size: 0.85rem;">Don't have an account?</span> 
            <a href="create.php" class="link-text" style="font-size: 0.85rem;">Sign Up</a>
        </div>

        <?= $message; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>