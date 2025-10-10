<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration - CHANGE THESE
$host = 'localhost';
$dbname = 'education_survey';
$username = 'root';        // Your MySQL username
$password = '';            // Your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Handle POST request (Submit survey)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO survey_responses (
                q1, q2, q3, q4, q5, q6, q7, q8, q9, q10,
                q11, q12, q13, q14, q15, q16, q17, q18, q19, q20, q21,
                year_level, gender, open_feedback, ip_address, user_agent, submitted_at
            ) VALUES (
                :q1, :q2, :q3, :q4, :q5, :q6, :q7, :q8, :q9, :q10,
                :q11, :q12, :q13, :q14, :q15, :q16, :q17, :q18, :q19, :q20, :q21,
                :year_level, :gender, :open_feedback, :ip_address, :user_agent, NOW()
            )
        ");
        
        // Bind rating questions (1-21)
        for ($i = 1; $i <= 21; $i++) {
            $stmt->bindValue(":q$i", isset($data["q$i"]) ? intval($data["q$i"]) : null);
        }
        
        // Bind demographics and other fields
        $stmt->bindValue(":year_level", $data["year_level"] ?? null);
        $stmt->bindValue(":gender", $data["gender"] ?? null);
        $stmt->bindValue(":open_feedback", $data["open_feedback"] ?? null);
        $stmt->bindValue(":ip_address", $_SERVER['REMOTE_ADDR'] ?? null);
        $stmt->bindValue(":user_agent", $_SERVER['HTTP_USER_AGENT'] ?? null);
        
        $stmt->execute();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Survey submitted successfully!',
            'response_id' => $pdo->lastInsertId()
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Handle GET request (Fetch dashboard data)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Get total responses
        $totalStmt = $pdo->query("SELECT COUNT(*) as total FROM survey_responses");
        $total = $totalStmt->fetch()['total'];
        
        // Get section averages
        $avgStmt = $pdo->query("
            SELECT 
                AVG((q1 + q2 + q3) / 3) as section_a_avg,
                AVG((q4 + q5 + q6) / 3) as section_b_avg,
                AVG((q7 + q8 + q9) / 3) as section_c_avg,
                AVG((q10 + q11 + q12) / 3) as section_d_avg,
                AVG((q13 + q14 + q15) / 3) as section_e_avg,
                AVG((q16 + q17 + q18) / 3) as section_f_avg,
                AVG((q19 + q20 + q21) / 3) as section_g_avg
            FROM survey_responses
            WHERE q1 IS NOT NULL
        ");
        $averages = $avgStmt->fetch();
        
        // Get question-by-question averages
        $questionAvgs = [];
        for ($i = 1; $i <= 21; $i++) {
            $stmt = $pdo->query("SELECT AVG(q$i) as avg FROM survey_responses WHERE q$i IS NOT NULL");
            $result = $stmt->fetch();
            $questionAvgs["q$i"] = $result ? round(floatval($result['avg']), 2) : 0;
        }
        
        // Get recent responses (without personal data)
        $recentStmt = $pdo->query("
            SELECT id, year_level, gender, submitted_at,
                   (q1 + q2 + q3 + q4 + q5 + q6 + q7 + q8 + q9 + q10 + 
                    q11 + q12 + q13 + q14 + q15 + q16 + q17 + q18 + q19 + q20 + q21) / 21 as overall_rating
            FROM survey_responses 
            ORDER BY submitted_at DESC 
            LIMIT 10
        ");
        $recentResponses = $recentStmt->fetchAll();
        
        // Get response distribution by date
        $distributionStmt = $pdo->query("
            SELECT 
                DATE(submitted_at) as date,
                COUNT(*) as count
            FROM survey_responses
            GROUP BY DATE(submitted_at)
            ORDER BY date DESC
            LIMIT 30
        ");
        $distribution = $distributionStmt->fetchAll();
        
        // Get demographic breakdown
        $demographicsStmt = $pdo->query("
            SELECT 
                year_level,
                gender,
                COUNT(*) as count,
                AVG((q1 + q2 + q3 + q4 + q5 + q6 + q7 + q8 + q9 + q10 + 
                     q11 + q12 + q13 + q14 + q15 + q16 + q17 + q18 + q19 + q20 + q21) / 21) as avg_rating
            FROM survey_responses
            WHERE q1 IS NOT NULL
            GROUP BY year_level, gender
            ORDER BY count DESC
        ");
        $demographics = $demographicsStmt->fetchAll();
        
        // Get satisfaction levels
        $satisfactionStmt = $pdo->query("
            SELECT 
                CASE 
                    WHEN (q19 + q20 + q21) / 3 >= 4.5 THEN 'Very Satisfied'
                    WHEN (q19 + q20 + q21) / 3 >= 3.5 THEN 'Satisfied'
                    WHEN (q19 + q20 + q21) / 3 >= 2.5 THEN 'Neutral'
                    WHEN (q19 + q20 + q21) / 3 >= 1.5 THEN 'Dissatisfied'
                    ELSE 'Very Dissatisfied'
                END as satisfaction_level,
                COUNT(*) as count
            FROM survey_responses
            WHERE q19 IS NOT NULL AND q20 IS NOT NULL AND q21 IS NOT NULL
            GROUP BY satisfaction_level
            ORDER BY count DESC
        ");
        $satisfaction = $satisfactionStmt->fetchAll();
        
        echo json_encode([
            'success' => true,
            'total_responses' => intval($total),
            'section_averages' => $averages,
            'question_averages' => $questionAvgs,
            'recent_responses' => $recentResponses,
            'distribution' => $distribution,
            'demographics' => $demographics,
            'satisfaction_levels' => $satisfaction,
            'generated_at' => date('Y-m-d H:i:s')
        ]);
        
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Handle invalid request methods
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST', 'OPTIONS'])) {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>