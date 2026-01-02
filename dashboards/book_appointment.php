<?php
session_start();
require_once('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $reason = $_POST['reason'];
    // Ensure you have a fallback if session isn't set for some reason
    $receptionist_id = $_SESSION['user_id'] ?? 0;

    try {
        // Insert appointment with explicit 'pending' status and current timestamp
        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, reason, status, appointment_date) VALUES (?, ?, ?, 'pending', NOW())");
        $result = $stmt->execute([$patient_id, $doctor_id, $reason]);

        if ($result) {
            echo "<script>alert('Appointment Booked Successfully!'); window.location.href='reception_dashboard.php';</script>";
        }
    } catch (PDOException $e) {
        // Log error and alert user
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
    }
}
?>