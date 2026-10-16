<?php
require_once 'config.php';

try {
    // Running a simple query directly since no user input is involved
    $result = $conn->query("SELECT id, name, email, created_at FROM users ORDER BY id DESC");
    $users = $result->fetch_all(MYSQLI_ASSOC); // Fetch rows directly as an associative array
    $result->free();
} catch (mysqli_sql_exception $e) {
    die("Could not retrieve users from the database.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            /* Matches the original blue/grey gradient */
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: flex-start; /* Changed from center to allow scrolling on long lists */
            padding: 3rem 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .data-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 900px; /* Made wider to accommodate the table */
            margin: auto;
        }
        .card-title {
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        .btn-primary {
            background-color: #4a90e2;
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #357abd;
        }
        .table th {
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .action-btn {
            padding: 0.3rem 0.8rem;
            font-size: 0.85rem;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="data-card">
        
        <!-- Header Section with Title and Add User Button -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <h2 class="card-title">Registered Users</h2>
            <a href="create.php" class="btn btn-primary">+ Add User</a>
        </div>

        <!-- Table Section -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Created At</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="fw-semibold text-muted">#<?= htmlspecialchars($user['id']) ?></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td class="text-muted"><small><?= htmlspecialchars(date('M j, Y', strtotime($user['created_at']))) ?></small></td>
                                <td class="text-end">
                                    <a href="update.php?id=<?= $user['id'] ?>" class="btn btn-outline-secondary action-btn me-1">Edit</a>
                                    <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-outline-danger action-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <em>No users found. Click "Add User" to get started.</em>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>