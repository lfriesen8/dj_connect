<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/main.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="../backend/process_register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="client">Client</option>
                <option value="dj">DJ</option>
                <option value="admin">Admin</option>
            </select>

            <!-- Optional fields for DJs -->
            <div id="dj_fields" style="display: none;">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="bio"></textarea>

                <label for="genres">Genres:</label>
                <input type="text" id="genres" name="genres" placeholder="e.g., house, techno">
            </div>

            <button type="submit">Register</button>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const djFields = document.getElementById('dj_fields');

        // Show additional fields for DJs
        roleSelect.addEventListener('change', () => {
            if (roleSelect.value === 'dj') {
                djFields.style.display = 'block';
            } else {
                djFields.style.display = 'none';
            }
        });
    </script>
</body>
</html>

