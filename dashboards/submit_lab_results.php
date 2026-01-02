<?php
session_start();
require_once('../includes/db.php');

// 1. Security Check: Ensure only Lab Techs can submit results
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'lab' && $_SESSION['role'] !== 'lab_tech')) {
    die("Unauthorized: You must be a Lab Technician to submit results.");
}

// 2. Process the Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values from the form
    // Note: We check for 'test_results' OR 'results' to match both versions of your HTML
    $request_id = $_POST['request_id'] ?? null;
    $findings   = $_POST['test_results'] ?? ($_POST['results'] ?? '');
    
    if (!$request_id) {
        die("Error: No Request ID provided.");
    }

    try {
        /**
         * 3. Database Transaction
         * We use a transaction to ensure that if the update fails, 
         * no partial data is saved.
         */
        $conn->beginTransaction();

        // 4. Update the Lab Request
        // Marks status as 'completed' so it appears in the Doctor's "Ready Lab Reports" modal
        $stmt = $conn->prepare("UPDATE lab_requests 
                                SET test_results = ?, status = 'completed' 
                                WHERE id = ?");
        
        $stmt->execute([$findings, $request_id]);

        /**
         * NOTE ON WORKFLOW:
         * We DO NOT change the appointment status here. 
         * The patient remains 'at_lab' in the Doctor's queue.
         * The Doctor will finalize the visit and click 'Discharge' later.
         */

        $conn->commit();

        // 5. Success Redirect
        // Redirecting to lab.php as per your current working setup
        header("Location: lab.php?status=success");
        exit;

    } catch (Exception $e) {
        // If anything goes wrong, cancel the changes
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        die("Database Error: " . $e->getMessage());
    }
} else {
    // If accessed directly without a POST request
    header("Location: lab.php");
    exit;
}
?>