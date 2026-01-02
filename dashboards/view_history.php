<?php
session_start();
require_once('../includes/db.php');

$student_id = $_GET['id'];

// Get Student Info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// Get Full Medical History
$stmt = $conn->prepare("SELECT mr.*, d.full_name as doctor_name 
                        FROM medical_records mr 
                        JOIN users d ON mr.doctor_id = d.id 
                        WHERE mr.patient_id = ? 
                        ORDER BY mr.visit_date DESC");
$stmt->execute([$student_id]);
$history = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medical History | <?= $student['full_name'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f5f5f7; padding: 40px; }
        .history-card { background: white; padding: 30px; border-radius: 20px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .back-btn { text-decoration: none; color: #af52de; font-weight: 800; margin-bottom: 20px; display: inline-block; }
    </style>
</head>
<body>
    <a href="receptionist.php" class="back-btn">← Back to Dashboard</a>
    <h1>Medical History: <?= $student['full_name'] ?></h1>
    <p>ID Number: <?= $student['username'] ?></p>
    <hr style="margin: 20px 0; opacity: 0.1;">

    <?php if(empty($history)): ?>
        <p>No previous records found for this student.</p>
    <?php else: ?>
        <?php foreach($history as $h): ?>
            <div class="history-card">
                <p><strong>Date:</strong> <?= date('M d, Y', strtotime($h['visit_date'])) ?></p>
                <p><strong>Diagnosis:</strong> <?= $h['diagnosis'] ?></p>
                <p><strong>Doctor:</strong> Dr. <?= $h['doctor_name'] ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>