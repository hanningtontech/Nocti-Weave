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
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <title>Admin Login - Dreams & Culture</title>
        <style>
            * {
                box-sizing: border-box;
            }
            
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                margin: 0;
                padding: 0;
                background: #f8f9fa;
                line-height: 1.6;
                -webkit-text-size-adjust: 100%;
                -webkit-font-smoothing: antialiased;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .login-box {
                max-width: 400px;
                width: 100%;
                margin: 20px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                padding: 32px 24px;
            }
            
            h2 {
                text-align: center;
                margin-top: 0;
                margin-bottom: 24px;
                color: #2c3e50;
                font-size: 1.8rem;
                font-weight: 700;
            }
            
            .error {
                color: #dc3545;
                background: #f8d7da;
                border: 1px solid #f5c6cb;
                border-radius: 8px;
                padding: 12px 16px;
                margin-bottom: 20px;
                font-size: 0.9rem;
            }
            
            input {
                width: 100%;
                padding: 16px 12px;
                margin-bottom: 20px;
                border: 2px solid #e9ecef;
                border-radius: 8px;
                font-size: 16px;
                font-family: inherit;
                background: #fff;
                transition: all 0.3s ease;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                touch-action: manipulation;
            }
            
            input:focus {
                border-color: #007bff;
                outline: none;
                background: #fff;
                box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
                transform: translateY(-1px);
            }
            
            button {
                width: 100%;
                padding: 16px 24px;
                background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
                color: #fff;
                border: none;
                border-radius: 8px;
                font-size: 18px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                touch-action: manipulation;
                min-height: 56px;
            }
            
            button:hover {
                background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
            }
            
            button:active {
                transform: translateY(0);
            }
            
            @media (max-width: 480px) {
                .login-box {
                    margin: 10px;
                    padding: 24px 16px;
                }
                
                h2 {
                    font-size: 1.5rem;
                }
                
                input, button {
                    padding: 14px 12px;
                    font-size: 16px;
                }
            }
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Dashboard - Dreams & Culture Questionnaire</title>
    <style>
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            line-height: 1.6;
            -webkit-text-size-adjust: 100%;
            -webkit-font-smoothing: antialiased;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 32px 24px;
            min-height: calc(100vh - 40px);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .logout {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .logout:hover {
            background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: #fff;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .response-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .response-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .response-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .response-id {
            background: #007bff;
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .response-date {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .response-summary {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-left: 4px solid #007bff;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-style: italic;
            color: #2c3e50;
            font-weight: 500;
        }
        
        .response-content {
            line-height: 1.7;
            color: #495057;
        }
        
        .section-title {
            color: #007bff;
            font-weight: 600;
            margin: 16px 0 8px 0;
            font-size: 1.1rem;
        }
        
        .response-text {
            margin-bottom: 12px;
        }
        
        .highlight {
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 4px;
            border-left: 3px solid #ffc107;
        }
        
        .emotion-highlight {
            background: #f8d7da;
            padding: 2px 6px;
            border-radius: 4px;
            border-left: 3px solid #dc3545;
            color: #721c24;
        }
        
        .consent-yes {
            background: #d4edda;
            padding: 2px 6px;
            border-radius: 4px;
            border-left: 3px solid #28a745;
            color: #155724;
            font-weight: 600;
        }
        
        .consent-no {
            background: #f8d7da;
            padding: 2px 6px;
            border-radius: 4px;
            border-left: 3px solid #dc3545;
            color: #721c24;
            font-weight: 600;
        }
        
        .cultural-insight {
            margin: 12px 0;
        }
        
        .insight-box, .dream-box, .symbol-box, .meaning-box, .influence-box, .comment-box {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            font-style: normal;
        }
        
        .insight-box {
            border-left: 4px solid #17a2b8;
            background: #f0fdff;
        }
        
        .dream-box {
            border-left: 4px solid #6f42c1;
            background: #f8f5ff;
        }
        
        .symbol-box {
            border-left: 4px solid #fd7e14;
            background: #fff8f0;
        }
        
        .meaning-box {
            border-left: 4px solid #20c997;
            background: #f0fff8;
        }
        
        .influence-box {
            border-left: 4px solid #e83e8c;
            background: #fff0f6;
        }
        
        .comment-box {
            border-left: 4px solid #6c757d;
            background: #f8f9fa;
        }
        
        .summary-section {
            margin-top: 40px;
            padding-top: 32px;
            border-top: 2px solid #e9ecef;
        }
        
        .summary-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .summary-header h2 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 12px;
        }
        
        .summary-description {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }
        
        .narrative-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .narrative-summary:hover {
            border-color: #007bff;
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.15);
            transform: translateY(-2px);
        }
        
        .narrative-header {
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid #007bff;
        }
        
        .narrative-header h3 {
            color: #007bff;
            font-size: 1.3rem;
            margin: 0;
            font-weight: 600;
        }
        
        .narrative-content {
            background: #fff;
            border-radius: 8px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .narrative-paragraph {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #2c3e50;
            margin: 0;
            text-align: justify;
            font-weight: 400;
        }
        
        .table-section {
            margin-top: 40px;
            padding-top: 32px;
            border-top: 2px solid #e9ecef;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .table-toggle {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .table-toggle:hover {
            background: #5a6268;
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: none;
        }
        
        .table-container.show {
            display: block;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }
        
        th, td {
            border: 1px solid #dee2e6;
            padding: 12px 8px;
            text-align: left;
            word-break: break-word;
            vertical-align: top;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #495057;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        td {
            font-size: 0.9rem;
            max-width: 200px;
        }
        
        .no-responses {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .no-responses-icon {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        /* Enhanced mobile responsiveness */
        @media (max-width: 768px) {
            body {
                padding: 0;
            }
            
            .container {
                max-width: 100%;
                margin: 0;
                border-radius: 0;
                padding: 20px 16px;
                min-height: 100vh;
                box-shadow: none;
            }
            
            .header {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .stats {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .stat-card {
                padding: 16px;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .response-card {
                padding: 16px;
                margin-bottom: 16px;
            }
            
            .response-header {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
            
            .narrative-summary {
                padding: 16px;
                margin-bottom: 20px;
            }
            
            .narrative-header h3 {
                font-size: 1.1rem;
            }
            
            .narrative-content {
                padding: 16px 18px;
            }
            
            .narrative-paragraph {
                font-size: 1rem;
                line-height: 1.7;
            }
            
            .summary-header h2 {
                font-size: 1.5rem;
            }
            
            .summary-description {
                font-size: 1rem;
            }
            
            .table-header {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
            
            th, td {
                padding: 8px 6px;
                font-size: 0.8rem;
            }
            
            td {
                max-width: 150px;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 16px 12px;
            }
            
            h1 {
                font-size: 1.3rem;
            }
            
            .response-card {
                padding: 12px;
            }
            
            .section-title {
                font-size: 1rem;
            }
            
            .narrative-summary {
                padding: 12px;
                margin-bottom: 16px;
            }
            
            .narrative-header h3 {
                font-size: 1rem;
            }
            
            .narrative-content {
                padding: 12px 14px;
            }
            
            .narrative-paragraph {
                font-size: 0.95rem;
                line-height: 1.6;
            }
            
            .summary-header h2 {
                font-size: 1.3rem;
            }
            
            .summary-description {
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 6px 4px;
                font-size: 0.75rem;
            }
            
            td {
                max-width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dreams & Culture Admin Dashboard</h1>
            <a href="?logout=1" class="logout">Logout</a>
        </div>
        
        <?php
        $csvFile = 'responses.csv';
        if (!file_exists($csvFile)) {
            echo '<div class="no-responses">
                    <div class="no-responses-icon">📋</div>
                    <h3>No responses yet</h3>
                    <p>Responses will appear here once users start submitting the questionnaire.</p>
                  </div>';
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
                    echo '<div class="no-responses">
                            <div class="no-responses-icon">📋</div>
                            <h3>No responses yet</h3>
                            <p>Responses will appear here once users start submitting the questionnaire.</p>
                          </div>';
                } else {
                    // Statistics
                    echo '<div class="stats">
                            <div class="stat-card">
                                <div class="stat-number">' . count($allData) . '</div>
                                <div class="stat-label">Total Responses</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">' . count(array_filter($allData, function($r) { return !empty($r['consent']) && strtolower($r['consent']) === 'yes'; })) . '</div>
                                <div class="stat-label">Consented for Research</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">' . count(array_filter($allData, function($r) { return !empty($r['share_with_ai']) && strtolower($r['share_with_ai']) === 'yes'; })) . '</div>
                                <div class="stat-label">Comfortable with AI</div>
                            </div>
                          </div>';
                    
                    // Show each response as a beautifully formatted card with intelligent summary
                    foreach ($allData as $index => $resp) {
                        echo '<div class="response-card">';
                        echo '<div class="response-header">';
                        echo '<div class="response-id">Response #' . ($index + 1) . '</div>';
                        echo '<div class="response-date">' . date('M j, Y') . '</div>';
                        echo '</div>';
                        
                        // Generate intelligent summary
                        $summary_parts = [];
                        if (!empty($resp['age']) && !empty($resp['gender']) && !empty($resp['location'])) {
                            $summary_parts[] = "A " . htmlspecialchars($resp['age']) . "-year-old " . htmlspecialchars($resp['gender']) . " from " . htmlspecialchars($resp['location']);
                        }
                        if (!empty($resp['tribe'])) {
                            $summary_parts[] = "of " . htmlspecialchars($resp['tribe']) . " cultural background";
                        }
                        if (!empty($resp['dream_frequency'])) {
                            $freq_text = strtolower($resp['dream_frequency']);
                            if ($freq_text == 'every_night' || $freq_text == 'every night') {
                                $summary_parts[] = "who remembers dreams every night";
                            } elseif ($freq_text == 'few_times_week' || $freq_text == 'a few times a week') {
                                $summary_parts[] = "who remembers dreams a few times a week";
                            } elseif ($freq_text == 'occasionally') {
                                $summary_parts[] = "who occasionally remembers dreams";
                            } elseif ($freq_text == 'rarely') {
                                $summary_parts[] = "who rarely remembers dreams";
                            } elseif ($freq_text == 'never') {
                                $summary_parts[] = "who never remembers dreams";
                            }
                        }
                        
                        if (!empty($summary_parts)) {
                            echo '<div class="response-summary">' . implode(', ', $summary_parts) . '.</div>';
                        }
                        
                        echo '<div class="response-content">';
                        
                        // Personal Information Section
                        if (!empty($resp['age']) || !empty($resp['gender']) || !empty($resp['tribe']) || !empty($resp['languages']) || !empty($resp['location'])) {
                            echo '<div class="section-title">👤 Personal Information</div>';
                            if (!empty($resp['age'])) echo '<div class="response-text">Age: <span class="highlight">' . htmlspecialchars($resp['age']) . '</span></div>';
                            if (!empty($resp['gender'])) echo '<div class="response-text">Gender: <span class="highlight">' . htmlspecialchars($resp['gender']) . '</span></div>';
                            if (!empty($resp['tribe'])) echo '<div class="response-text">Cultural Background: <span class="highlight">' . htmlspecialchars($resp['tribe']) . '</span></div>';
                            if (!empty($resp['languages'])) echo '<div class="response-text">Languages: <span class="highlight">' . htmlspecialchars($resp['languages']) . '</span></div>';
                            if (!empty($resp['location'])) echo '<div class="response-text">Location: <span class="highlight">' . htmlspecialchars($resp['location']) . '</span></div>';
                        }
                        
                        // Dream Habits Section
                        if (!empty($resp['dream_frequency']) || !empty($resp['tell_someone']) || !empty($resp['tell_whom']) || !empty($resp['record_dreams'])) {
                            echo '<div class="section-title">🌙 Dream Habits</div>';
                            if (!empty($resp['dream_frequency'])) echo '<div class="response-text">Dream Frequency: <span class="highlight">' . htmlspecialchars($resp['dream_frequency']) . '</span></div>';
                            if (!empty($resp['tell_someone'])) {
                                $tellText = strtolower($resp['tell_someone']) == 'yes' ? 'Usually tells someone about dreams' : 'Does not usually tell anyone about dreams';
                                echo '<div class="response-text">Sharing Dreams: <span class="highlight">' . $tellText . '</span></div>';
                            }
                            if (!empty($resp['tell_whom'])) echo '<div class="response-text">Tells: <span class="highlight">' . htmlspecialchars($resp['tell_whom']) . '</span></div>';
                            if (!empty($resp['record_dreams'])) {
                                $recordText = strtolower($resp['record_dreams']) == 'yes' ? 'Records dreams' : 'Does not record dreams';
                                echo '<div class="response-text">Recording: <span class="highlight">' . $recordText . '</span></div>';
                            }
                        }
                        
                        // Cultural Beliefs Section - Prioritized for cultural research
                        if (!empty($resp['special_meanings']) || !empty($resp['explanation']) || !empty($resp['stories']) || !empty($resp['go_to'])) {
                            echo '<div class="section-title">🏛️ Cultural Beliefs About Dreams</div>';
                            if (!empty($resp['special_meanings'])) echo '<div class="response-text">Dreams Have Special Meanings: <span class="highlight">' . htmlspecialchars($resp['special_meanings']) . '</span></div>';
                            if (!empty($resp['explanation'])) {
                                echo '<div class="response-text cultural-insight">Cultural Explanation: <div class="insight-box">' . nl2br(htmlspecialchars($resp['explanation'])) . '</div></div>';
                            }
                            if (!empty($resp['stories'])) {
                                echo '<div class="response-text cultural-insight">Traditional Stories/Proverbs: <div class="insight-box">' . nl2br(htmlspecialchars($resp['stories'])) . '</div></div>';
                            }
                            if (!empty($resp['go_to'])) echo '<div class="response-text">Goes to for Interpretation: <span class="highlight">' . htmlspecialchars($resp['go_to']) . '</span></div>';
                        }
                        
                        // Personal Dream Experiences Section - Enhanced presentation
                        if (!empty($resp['dream_description']) || !empty($resp['dream_feeling']) || !empty($resp['attach_meaning']) || !empty($resp['meaning_description'])) {
                            echo '<div class="section-title">💭 Personal Dream Experiences</div>';
                            if (!empty($resp['dream_description'])) {
                                echo '<div class="response-text dream-narrative">Dream Description: <div class="dream-box">' . nl2br(htmlspecialchars($resp['dream_description'])) . '</div></div>';
                            }
                            if (!empty($resp['dream_feeling'])) echo '<div class="response-text">Feelings: <span class="emotion-highlight">' . htmlspecialchars($resp['dream_feeling']) . '</span></div>';
                            if (!empty($resp['attach_meaning'])) echo '<div class="response-text">Attached Meaning: <span class="highlight">' . htmlspecialchars($resp['attach_meaning']) . '</span></div>';
                            if (!empty($resp['meaning_description'])) {
                                echo '<div class="response-text">Meaning Description: <div class="meaning-box">' . nl2br(htmlspecialchars($resp['meaning_description'])) . '</div></div>';
                            }
                        }
                        
                        // Dream Symbols Section - Enhanced for research value
                        if (!empty($resp['symbols']) || !empty($resp['symbol_meanings']) || !empty($resp['omens_examples'])) {
                            echo '<div class="section-title">🔮 Dream Symbols and Meanings</div>';
                            if (!empty($resp['symbols'])) {
                                echo '<div class="response-text symbol-data">Recurring Symbols: <div class="symbol-box">' . nl2br(htmlspecialchars($resp['symbols'])) . '</div></div>';
                            }
                            if (!empty($resp['symbol_meanings'])) {
                                echo '<div class="response-text symbol-data">Symbol Meanings: <div class="symbol-box">' . nl2br(htmlspecialchars($resp['symbol_meanings'])) . '</div></div>';
                            }
                            if (!empty($resp['omens_examples'])) {
                                echo '<div class="response-text cultural-insight">Dreams as Omens: <div class="insight-box">' . nl2br(htmlspecialchars($resp['omens_examples'])) . '</div></div>';
                            }
                        }
                        
                        // Impact of Dreams Section
                        if (!empty($resp['influenced']) || !empty($resp['influence_description']) || !empty($resp['rituals']) || !empty($resp['ritual_actions'])) {
                            echo '<div class="section-title">⚡ Impact of Dreams</div>';
                            if (!empty($resp['influenced'])) echo '<div class="response-text">Dreams Influenced Actions: <span class="highlight">' . htmlspecialchars($resp['influenced']) . '</span></div>';
                            if (!empty($resp['influence_description'])) {
                                echo '<div class="response-text">Influence Description: <div class="influence-box">' . nl2br(htmlspecialchars($resp['influence_description'])) . '</div></div>';
                            }
                            if (!empty($resp['rituals'])) {
                                $ritualText = strtolower($resp['rituals']) == 'yes' ? 'Performs rituals/prayers after certain dreams' : 'Does not perform rituals/prayers after dreams';
                                echo '<div class="response-text">Rituals: <span class="highlight">' . $ritualText . '</span></div>';
                            }
                            if (!empty($resp['ritual_actions'])) {
                                echo '<div class="response-text cultural-insight">Ritual Actions: <div class="insight-box">' . nl2br(htmlspecialchars($resp['ritual_actions'])) . '</div></div>';
                            }
                        }
                        
                        // Sharing and Consent Section
                        if (!empty($resp['share_with_ai']) || !empty($resp['additional_comments']) || !empty($resp['consent'])) {
                            echo '<div class="section-title">🤝 Sharing and Consent</div>';
                            if (!empty($resp['share_with_ai'])) echo '<div class="response-text">Comfortable Sharing with AI: <span class="highlight">' . htmlspecialchars($resp['share_with_ai']) . '</span></div>';
                            if (!empty($resp['additional_comments'])) {
                                echo '<div class="response-text">Additional Comments: <div class="comment-box">' . nl2br(htmlspecialchars($resp['additional_comments'])) . '</div></div>';
                            }
                            if (!empty($resp['consent'])) {
                                $consentClass = strtolower($resp['consent']) == 'yes' ? 'consent-yes' : 'consent-no';
                                echo '<div class="response-text">Research Consent: <span class="' . $consentClass . '">' . htmlspecialchars($resp['consent']) . '</span></div>';
                            }
                        }
                        
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    // Comprehensive Summary Section
                    echo '<div class="summary-section">';
                    echo '<div class="summary-header">';
                    echo '<h2>📝 Comprehensive Response Summaries</h2>';
                    echo '<p class="summary-description">Each response is presented as a flowing narrative paragraph that captures the essence of the participant\'s cultural background, dream experiences, and beliefs.</p>';
                    echo '</div>';
                    
                    foreach ($allData as $index => $resp) {
                        echo '<div class="narrative-summary">';
                        echo '<div class="narrative-header">';
                        echo '<h3>Response #' . ($index + 1) . ' - Cultural Dream Narrative</h3>';
                        echo '</div>';
                        
                        // Generate comprehensive narrative paragraph
                        $narrative = [];
                        
                        // Personal introduction
                        $intro = "I am";
                        if (!empty($resp['age'])) {
                            $intro .= " a " . htmlspecialchars($resp['age']) . "-year-old";
                        }
                        if (!empty($resp['gender'])) {
                            $intro .= " " . htmlspecialchars($resp['gender']);
                        }
                        if (!empty($resp['tribe'])) {
                            $intro .= " from " . htmlspecialchars($resp['tribe']) . " culture";
                        }
                        if (!empty($resp['languages'])) {
                            $intro .= ". I speak " . htmlspecialchars($resp['languages']);
                        }
                        if (!empty($resp['location'])) {
                            $intro .= " and currently live in " . htmlspecialchars($resp['location']);
                        }
                        $intro .= ".";
                        $narrative[] = $intro;
                        
                        // Dream habits narrative
                        if (!empty($resp['dream_frequency'])) {
                            $freq_text = strtolower($resp['dream_frequency']);
                            if ($freq_text == 'every_night' || $freq_text == 'every night') {
                                $dream_habit = "When it comes to my dreams, I remember them every single night.";
                            } elseif ($freq_text == 'few_times_week' || $freq_text == 'a few times a week') {
                                $dream_habit = "I remember my dreams a few times each week.";
                            } elseif ($freq_text == 'occasionally') {
                                $dream_habit = "I occasionally remember my dreams.";
                            } elseif ($freq_text == 'rarely') {
                                $dream_habit = "I rarely remember my dreams.";
                            } elseif ($freq_text == 'never') {
                                $dream_habit = "I never remember my dreams.";
                            } else {
                                $dream_habit = "Regarding dream frequency, " . htmlspecialchars($resp['dream_frequency']) . ".";
                            }
                            
                            if (!empty($resp['tell_someone'])) {
                                if (strtolower($resp['tell_someone']) == 'yes') {
                                    $dream_habit .= " When I do remember them, I usually share them with others";
                                    if (!empty($resp['tell_whom'])) {
                                        $dream_habit .= ", particularly with " . htmlspecialchars($resp['tell_whom']);
                                    }
                                    $dream_habit .= ".";
                                } else {
                                    $dream_habit .= " When I do remember them, I typically keep them to myself.";
                                }
                            }
                            
                            if (!empty($resp['record_dreams'])) {
                                if (strtolower($resp['record_dreams']) == 'yes') {
                                    $dream_habit .= " I also make it a practice to write down or record my dreams.";
                                } else {
                                    $dream_habit .= " I don\'t usually write down or record my dreams.";
                                }
                            }
                            
                            $narrative[] = $dream_habit;
                        }
                        
                        // Cultural beliefs narrative
                        if (!empty($resp['special_meanings']) || !empty($resp['explanation'])) {
                            $cultural_belief = "";
                            if (!empty($resp['special_meanings'])) {
                                $meaning_response = strtolower($resp['special_meanings']);
                                if ($meaning_response == 'yes') {
                                    $cultural_belief = "In my culture and family, we strongly believe that dreams carry special meanings and significance.";
                                } elseif ($meaning_response == 'no') {
                                    $cultural_belief = "In my culture and family, dreams are not typically viewed as having special meanings.";
                                } else {
                                    $cultural_belief = "Regarding whether dreams have special meanings in my culture, I would say " . htmlspecialchars($resp['special_meanings']) . ".";
                                }
                            }
                            
                            if (!empty($resp['explanation'])) {
                                $cultural_belief .= " " . htmlspecialchars($resp['explanation']);
                            }
                            
                            if (!empty($resp['stories'])) {
                                $cultural_belief .= " Our community has traditional stories and sayings about dreams: " . htmlspecialchars($resp['stories']);
                            }
                            
                            if (!empty($resp['go_to'])) {
                                $cultural_belief .= " When I need dream interpretation or advice, I turn to " . htmlspecialchars($resp['go_to']) . ".";
                            }
                            
                            if (!empty($cultural_belief)) {
                                $narrative[] = $cultural_belief;
                            }
                        }
                        
                        // Personal dream experience narrative
                        if (!empty($resp['dream_description']) || !empty($resp['dream_feeling'])) {
                            $dream_experience = "";
                            if (!empty($resp['dream_description'])) {
                                $dream_experience = "One dream that stands out clearly in my memory is this: " . htmlspecialchars($resp['dream_description']);
                            }
                            
                            if (!empty($resp['dream_feeling'])) {
                                $dream_experience .= " During and after this dream, I felt " . htmlspecialchars($resp['dream_feeling']) . ".";
                            }
                            
                            if (!empty($resp['attach_meaning'])) {
                                if (strtolower($resp['attach_meaning']) == 'yes') {
                                    $dream_experience .= " Both I and others attached meaning to this dream.";
                                    if (!empty($resp['meaning_description'])) {
                                        $dream_experience .= " The meaning we found was: " . htmlspecialchars($resp['meaning_description']);
                                    }
                                } else {
                                    $dream_experience .= " Neither I nor others attached any particular meaning to this dream.";
                                }
                            }
                            
                            if (!empty($dream_experience)) {
                                $narrative[] = $dream_experience;
                            }
                        }
                        
                        // Symbols and meanings narrative
                        if (!empty($resp['symbols']) || !empty($resp['symbol_meanings'])) {
                            $symbols_narrative = "";
                            if (!empty($resp['symbols'])) {
                                $symbols_narrative = "In my dreams, certain symbols, animals, or people appear regularly: " . htmlspecialchars($resp['symbols']) . ".";
                            }
                            
                            if (!empty($resp['symbol_meanings'])) {
                                $symbols_narrative .= " To me and my culture, these symbols mean: " . htmlspecialchars($resp['symbol_meanings']) . ".";
                            }
                            
                            if (!empty($resp['omens_examples'])) {
                                $symbols_narrative .= " In our community, certain dreams are considered omens: " . htmlspecialchars($resp['omens_examples']);
                            }
                            
                            if (!empty($symbols_narrative)) {
                                $narrative[] = $symbols_narrative;
                            }
                        }
                        
                        // Impact and rituals narrative
                        if (!empty($resp['influenced']) || !empty($resp['rituals'])) {
                            $impact_narrative = "";
                            if (!empty($resp['influenced'])) {
                                if (strtolower($resp['influenced']) == 'yes') {
                                    $impact_narrative = "Dreams have indeed influenced my real-life decisions and actions.";
                                    if (!empty($resp['influence_description'])) {
                                        $impact_narrative .= " Specifically: " . htmlspecialchars($resp['influence_description']);
                                    }
                                } else {
                                    $impact_narrative = "Dreams have not significantly influenced my real-life decisions or actions.";
                                }
                            }
                            
                            if (!empty($resp['rituals'])) {
                                if (strtolower($resp['rituals']) == 'yes') {
                                    $impact_narrative .= " After certain dreams, I do perform rituals, pray, or seek help.";
                                    if (!empty($resp['ritual_actions'])) {
                                        $impact_narrative .= " The actions I take include: " . htmlspecialchars($resp['ritual_actions']);
                                    }
                                } else {
                                    $impact_narrative .= " I don\'t typically perform rituals or seek help after dreams.";
                                }
                            }
                            
                            if (!empty($impact_narrative)) {
                                $narrative[] = $impact_narrative;
                            }
                        }
                        
                        // Sharing and consent narrative
                        if (!empty($resp['share_with_ai']) || !empty($resp['consent'])) {
                            $sharing_narrative = "";
                            if (!empty($resp['share_with_ai'])) {
                                $ai_comfort = strtolower($resp['share_with_ai']);
                                if ($ai_comfort == 'yes') {
                                    $sharing_narrative = "I am comfortable sharing my dreams with AI for research and cultural understanding.";
                                } elseif ($ai_comfort == 'no') {
                                    $sharing_narrative = "I am not comfortable sharing my dreams with AI for research purposes.";
                                } else {
                                    $sharing_narrative = "Regarding sharing dreams with AI, I feel " . htmlspecialchars($resp['share_with_ai']) . ".";
                                }
                            }
                            
                            if (!empty($resp['consent'])) {
                                if (strtolower($resp['consent']) == 'yes') {
                                    $sharing_narrative .= " I give my permission for my anonymous responses to be used to improve cultural knowledge systems.";
                                } else {
                                    $sharing_narrative .= " I do not give permission for my responses to be used for research purposes.";
                                }
                            }
                            
                            if (!empty($resp['additional_comments'])) {
                                $sharing_narrative .= " Additionally, I would like to share: " . htmlspecialchars($resp['additional_comments']);
                            }
                            
                            if (!empty($sharing_narrative)) {
                                $narrative[] = $sharing_narrative;
                            }
                        }
                        
                        // Combine all narrative parts into flowing paragraphs
                        $full_narrative = implode(" ", $narrative);
                        
                        echo '<div class="narrative-content">';
                        echo '<p class="narrative-paragraph">' . $full_narrative . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    echo '</div>';
                }
            } else {
                echo '<div class="no-responses">
                        <div class="no-responses-icon">❌</div>
                        <h3>Error</h3>
                        <p>Could not open responses file.</p>
                      </div>';
            }
        }
        ?>
    </div>
    
    <script>
        // Add smooth scrolling for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const responseCards = document.querySelectorAll('.response-card');
            const narrativeSummaries = document.querySelectorAll('.narrative-summary');
            
            responseCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                });
            });
            
            narrativeSummaries.forEach(summary => {
                summary.addEventListener('click', function() {
                    this.style.transform = 'scale(1.01)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 200);
                });
            });
        });
    </script>
</body>
</html>

