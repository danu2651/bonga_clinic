<?php
session_start();
require_once('../includes/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p_id         = $_POST['patient_id'] ?? null;
    $apt_id       = $_POST['apt_id'] ?? null;
    $diagnosis    = $_POST['diagnosis'] ?? '';
    $prescription = $_POST['prescription'] ?? '';
    $disposition  = $_POST['disposition'] ?? ''; 
    $lab_test     = $_POST['lab_request'] ?? '';
    $ref_to       = $_POST['referral_to'] ?? '';
    $ref_reason   = $_POST['referral_reason'] ?? '';
    $doctor_id    = $_SESSION['user_id'] ?? null;

    if (!$p_id || !$apt_id || !$doctor_id) {
        echo json_encode(['success' => false, 'message' => 'Missing IDs']); exit;
    }

    try {
        $conn->beginTransaction();

        if ($disposition === 'lab') {
            // Keep in queue, move to lab
            $conn->prepare("UPDATE appointments SET status = 'at_lab' WHERE id = ?")->execute([$apt_id]);
            $conn->prepare("INSERT INTO lab_requests (appointment_id, patient_id, doctor_id, test_name, status) VALUES (?, ?, ?, ?, 'pending')")
                 ->execute([$apt_id, $p_id, $doctor_id, $lab_test]);
            $msg = "Sent to Lab.";
        } 
        elseif ($disposition === 'referral') {
            // Save record with referral details and close
            $diag_text = $diagnosis . " [REFERRAL: $ref_to - $ref_reason]";
            $conn->prepare("INSERT INTO medical_records (patient_id, doctor_id, diagnosis, prescription, lab_request) VALUES (?, ?, ?, ?, ?)")
                 ->execute([$p_id, $doctor_id, $diag_text, $prescription, 'N/A']);
            $conn->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?")->execute([$apt_id]);
            $msg = "Referral processed.";
        }
        else {
            // Normal Discharge
            $conn->prepare("INSERT INTO medical_records (patient_id, doctor_id, diagnosis, prescription, lab_request) VALUES (?, ?, ?, ?, ?)")
                 ->execute([$p_id, $doctor_id, $diagnosis, $prescription, $lab_test]);
            $conn->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?")->execute([$apt_id]);
            $msg = "Patient discharged.";
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => $msg]);
    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}