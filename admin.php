<?php
session_start();

// Set the admin password
$admin_password = 'admin123';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit();
}

// Handle login
if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'Incorrect password.';
    }
}

// If not logged in, show login form
if (empty($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Admin Login</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; }
            .login-box { max-width: 400px; margin: 100px auto; border: 1px solid #ccc; padding: 30px; border-radius: 8px; }
            input { width: 100%; padding: 8px; margin-bottom: 15px; }
            button { padding: 10px 20px; }
            .error { color: red; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h2>Admin Login</h2>
            <?php if (!empty($error)) echo '<div class="error">'.$error.'</div>'; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Enter admin password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// If logged in, show responses
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin - Submitted Responses</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f0f0f0; }
        .logout { float: right; }
    </style>
</head>
<body>
    <h1>Submitted Responses <a href="?logout=1" class="logout">Logout</a></h1>
    <?php
    $csvFile = 'responses.csv';
    if (!file_exists($csvFile)) {
        echo '<p>No responses yet.</p>';
    } else {
        if (($handle = fopen($csvFile, 'r')) !== false) {
            echo '<table>';
            $row = 0;
            while (($data = fgetcsv($handle)) !== false) {
                if ($row === 0) {
                    // Header row
                    echo '<tr>';
                    foreach ($data as $header) {
                        echo '<th>' . htmlspecialchars($header) . '</th>';
                    }
                    echo '</tr>';
                } else {
                    echo '<tr>';
                    foreach ($data as $cell) {
                        echo '<td>' . nl2br(htmlspecialchars($cell)) . '</td>';
                    }
                    echo '</tr>';
                }
                $row++;
            }
            fclose($handle);
            echo '</table>';
        } else {
            echo '<p>Could not open responses file.</p>';
        }
    }
    ?>
</body>
</html>