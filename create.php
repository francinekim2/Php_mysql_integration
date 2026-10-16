<?php
require_once 'config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $email);
            $stmt->execute();
            
            $message = '<div class="alert alert-success mt-3 py-2" role="alert"><small>User registered successfully! ID: ' . $conn->insert_id . '</small></div>';
            $stmt->close();
            
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { 
                $message = '<div class="alert alert-danger mt-3 py-2" role="alert"><small>Error: Email already registered.</small></div>';
            } else {
                $message = '<div class="alert alert-danger mt-3 py-2" role="alert"><small>Error saving profile.</small></div>';
            }
        }
    } else {
        $message = '<div class="alert alert-warning mt-3 py-2" role="alert"><small>Please provide a valid name and email.</small></div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Get Started</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 2rem; 
            width: 100%;
            max-width: 380px; 
            margin: auto;
        }
        .register-title {
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 0.25rem;
            text-align: center;
            font-size: 1.5rem; 
        }
        .register-subtitle {
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
    <div class="register-card">
        <h2 class="register-title">Let's Get Started!</h2>
        <p class="register-subtitle">It only takes a moment to sign up and begin something amazing.</p>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label text-muted fw-semibold">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label text-muted fw-semibold">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
        
        <div class="text-center mt-3">
            <span class="text-muted" style="font-size: 0.85rem;">Already have an account?</span> 
            <a href="sign.php" class="link-text" style="font-size: 0.85rem;">Sign In</a>
        </div>

        <?= $message; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>