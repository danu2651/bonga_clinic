<?php
// Turn on error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1); 

header('Content-Type: application/json');
session_start();

// 1. Check if DB file exists
if (!file_exists('../includes/db.php')) {
    echo json_encode(["error" => "File path error: ../includes/db.php not found"]);
    exit;
}

require_once('../includes/db.php');

$p_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

try {
    // 2. We try a very simple query. 
    // IF THIS CRASHES: It means your table is not named 'medical_records' 
    // or your column is not 'patient_id'
    $stmt = $conn->prepare("SELECT * FROM medical_records WHERE patient_id = ? ORDER BY id DESC");
    $stmt->execute([$p_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $history = [];
    foreach($rows as $row) {
        $history[] = [
            // This handles different possible column names
            "visit_date"   => $row['created_at'] ?? $row['date'] ?? 'Unknown Date',
            "diagnosis"    => $row['diagnosis'] ?? 'No Diagnosis',
            "prescription" => $row['prescription'] ?? $row['treatment'] ?? 'None',
            "doctor_name"  => "Staff" 
        ];
    }

    echo json_encode($history);

} catch (PDOException $e) {
    // This will tell us EXACTLY what is wrong with the SQL
    http_response_code(500);
    echo json_encode([
        "error" => "Database Error",
        "details" => $e->getMessage()
    ]);
}
?>