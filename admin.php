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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; background: #f8f9fa; }
            .login-box { max-width: 400px; margin: 100px auto; border: 1px solid #ccc; padding: 30px; border-radius: 8px; background: #fff; }
            input { width: 100%; padding: 10px; margin-bottom: 15px; font-size: 16px; }
            button { padding: 12px 24px; font-size: 18px; background: #007bff; color: #fff; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
            button:hover { background: #0056b3; }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Submitted Responses</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f8f9fa; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 24px 20px; }
        h1 { text-align: center; margin-top: 0; }
        .logout { float: right; font-size: 16px; color: #007bff; text-decoration: none; margin-top: 8px; }
        .logout:hover { text-decoration: underline; }
        .summary { background: #f1f3f6; border-radius: 8px; padding: 16px; margin-bottom: 24px; font-size: 1.08em; line-height: 1.7; }
        table { border-collapse: collapse; width: 100%; margin-top: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; word-break: break-word; }
        th { background: #f0f0f0; }
        @media (max-width: 700px) {
            .container { max-width: 98vw; margin: 10px 1vw; padding: 12px 2vw; }
            table, th, td { font-size: 13px; }
            .summary { font-size: 1em; }
        }
        @media (max-width: 400px) {
            .container { padding: 6px 1vw; }
            h1 { font-size: 1.1em; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submitted Responses <a href="?logout=1" class="logout">Logout</a></h1>
        <?php
        $csvFile = 'responses.csv';
        if (!file_exists($csvFile)) {
            echo '<p>No responses yet.</p>';
        } else {
            if (($handle = fopen($csvFile, 'r')) !== false) {
                $row = 0;
                $headers = [];
                $allData = [];
                while (($data = fgetcsv($handle)) !== false) {
                    if ($row === 0) {
                        $headers = $data;
                    } else {
                        $allData[] = array_combine($headers, $data);
                    }
                    $row++;
                }
                fclose($handle);
                if (count($allData) === 0) {
                    echo '<p>No responses yet.</p>';
                } else {
                    // Show each response as a human-readable paragraph
                    foreach ($allData as $resp) {
                        // Build a readable summary
                        $summary = [];
                        if (!empty($resp['age'])) $summary[] = "My age is " . htmlspecialchars($resp['age']) . ".";
                        if (!empty($resp['gender'])) $summary[] = "I am " . htmlspecialchars($resp['gender']) . " in gender.";
                        if (!empty($resp['tribe'])) $summary[] = "I am from the " . htmlspecialchars($resp['tribe']) . " tribe/cultural background.";
                        if (!empty($resp['languages'])) $summary[] = "I speak " . htmlspecialchars($resp['languages']) . " at home.";
                        if (!empty($resp['location'])) $summary[] = "I currently live in " . htmlspecialchars($resp['location']) . ".";
                        if (!empty($resp['dream_frequency'])) $summary[] = "I remember my dreams: " . htmlspecialchars($resp['dream_frequency']) . ".";
                        if (!empty($resp['tell_someone'])) $summary[] = "When I remember a dream, I " . (strtolower($resp['tell_someone']) == 'yes' ? 'usually tell someone' : 'do not usually tell anyone') . ".";
                        if (!empty($resp['tell_whom'])) $summary[] = "I tell: " . htmlspecialchars($resp['tell_whom']) . ".";
                        if (!empty($resp['record_dreams'])) $summary[] = "I " . (strtolower($resp['record_dreams']) == 'yes' ? 'record' : 'do not record') . " my dreams.";
                        if (!empty($resp['special_meanings'])) $summary[] = "In my culture/family, dreams have special meanings: " . htmlspecialchars($resp['special_meanings']) . ".";
                        if (!empty($resp['explanation'])) $summary[] = "Explanation: " . htmlspecialchars($resp['explanation']);
                        if (!empty($resp['stories'])) $summary[] = "Traditional stories/proverbs/sayings: " . htmlspecialchars($resp['stories']);
                        if (!empty($resp['go_to'])) $summary[] = "I go to " . htmlspecialchars($resp['go_to']) . " for dream interpretation/advice.";
                        if (!empty($resp['dream_description'])) $summary[] = "A dream I remember: " . htmlspecialchars($resp['dream_description']);
                        if (!empty($resp['dream_feeling'])) $summary[] = "I felt: " . htmlspecialchars($resp['dream_feeling']);
                        if (!empty($resp['attach_meaning'])) $summary[] = "Did I/others attach meaning: " . htmlspecialchars($resp['attach_meaning']) . ".";
                        if (!empty($resp['meaning_description'])) $summary[] = "Meaning: " . htmlspecialchars($resp['meaning_description']);
                        if (!empty($resp['symbols'])) $summary[] = "Symbols/animals/people in dreams: " . htmlspecialchars($resp['symbols']);
                        if (!empty($resp['symbol_meanings'])) $summary[] = "Symbol meanings: " . htmlspecialchars($resp['symbol_meanings']);
                        if (!empty($resp['omens_examples'])) $summary[] = "Dreams considered omens: " . htmlspecialchars($resp['omens_examples']);
                        if (!empty($resp['influenced'])) $summary[] = "Dreams influenced my actions: " . htmlspecialchars($resp['influenced']) . ".";
                        if (!empty($resp['influence_description'])) $summary[] = "Influence description: " . htmlspecialchars($resp['influence_description']);
                        if (!empty($resp['rituals'])) $summary[] = "After certain dreams, I " . (strtolower($resp['rituals']) == 'yes' ? 'perform rituals/pray/seek help' : 'do not perform rituals/pray/seek help') . ".";
                        if (!empty($resp['ritual_actions'])) $summary[] = "Actions taken: " . htmlspecialchars($resp['ritual_actions']);
                        if (!empty($resp['share_with_ai'])) $summary[] = "Comfortable sharing with AI: " . htmlspecialchars($resp['share_with_ai']) . ".";
                        if (!empty($resp['additional_comments'])) $summary[] = "Additional comments: " . htmlspecialchars($resp['additional_comments']);
                        if (!empty($resp['consent'])) $summary[] = "Consent for use: " . htmlspecialchars($resp['consent']) . ".";
                        echo '<div class="summary">' . implode(' ', $summary) . '</div>';
                    }
                    // Table view for detailed review
                    echo '<h2 style="margin-top:40px;">Raw Table View</h2>';
                    echo '<div style="overflow-x:auto;"><table>';
                    echo '<tr>';
                    foreach ($headers as $header) {
                        echo '<th>' . htmlspecialchars($header) . '</th>';
                    }
                    echo '</tr>';
                    foreach ($allData as $resp) {
                        echo '<tr>';
                        foreach ($headers as $header) {
                            echo '<td>' . nl2br(htmlspecialchars($resp[$header] ?? '')) . '</td>';
                        }
                        echo '</tr>';
                    }
                    echo '</table></div>';
                }
            } else {
                echo '<p>Could not open responses file.</p>';
            }
        }
        ?>
    </div>
</body>
</html>
