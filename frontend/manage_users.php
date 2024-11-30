<?php
require('../backend/connect.php');

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Fetch all users
$query_users = "SELECT id, username, role FROM users";
$stmt_users = $db->prepare($query_users);
$stmt_users->execute();
$users = $stmt_users->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Manage Users</title>
</head>
<body>
    <header>
        <div class="navbar">
            <a href="admin_dashboard.php">Return to Admin Dashboard</a>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main>
        <h1>Manage Users</h1>

       <!-- Display Feedback Messages -->
<?php if (isset($_SESSION['message'])): ?>
    <p class="<?= isset($_SESSION['message_type']) ? 'feedback ' . htmlspecialchars($_SESSION['message_type']) : 'feedback'; ?>">
        <?= htmlspecialchars($_SESSION['message']); ?>
    </p>
    <?php 
        // Clear the message after it's displayed
        unset($_SESSION['message'], $_SESSION['message_type']); 
    ?>
<?php endif; ?>


        <!-- Add User Form -->
        <section>
            <h2>Add User</h2>
            <form action="../backend/add_user.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                
                <label for="role">Role:</label>
                <select name="role" id="role" required>
                    <option value="admin">Admin</option>
                    <option value="dj">DJ</option>
                    <option value="client">Client</option>
                </select>
                
                <button type="submit">Add User</button>
            </form>
        </section>

        <!-- User List -->
        <section>
            <h2>Users</h2>
            <?php if (!empty($users)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']); ?></td>
                                <td><?= htmlspecialchars($user['username']); ?></td>
                                <td><?= htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <!-- Update Form -->
                                    <form action="../backend/update_user.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']); ?>">
                                        
                                        <label for="username-<?= $user['id']; ?>">Username:</label>
                                        <input type="text" name="username" id="username-<?= $user['id']; ?>" value="<?= htmlspecialchars($user['username']); ?>" required>
                                        
                                        <label for="role-<?= $user['id']; ?>">Role:</label>
                                        <select name="role" id="role-<?= $user['id']; ?>" required>
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                            <option value="dj" <?= $user['role'] === 'dj' ? 'selected' : ''; ?>>DJ</option>
                                            <option value="client" <?= $user['role'] === 'client' ? 'selected' : ''; ?>>Client</option>
                                        </select>
                                        
                                        <button type="submit">Update</button>
                                    </form>

                                    <!-- Delete Form -->
                                    <form action="../backend/delete_user.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']); ?>">
                                        <button type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
