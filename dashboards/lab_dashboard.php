<?php
session_start();
require_once('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'lab_tech') {
    header("Location: ../auth/login.php"); exit;
}

// Fetch all pending lab requests
$stmt = $conn->prepare("SELECT lr.*, u.full_name as patient_name, u.username as student_id 
                        FROM lab_requests lr 
                        JOIN users u ON lr.patient_id = u.id 
                        WHERE lr.status = 'pending' 
                        ORDER BY lr.created_at ASC");
$stmt->execute();
$requests = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Terminal | Bonga Clinic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --lab: #5856d6; --bg: #f5f5f7; }
        body { font-family: sans-serif; background: var(--bg); padding: 40px; }
        .lab-card { background: white; padding: 20px; border-radius: 15px; max-width: 800px; margin: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        input[type="text"] { width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ddd; }
        .btn-send { background: var(--lab); color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="lab-card">
        <h2><i class="fa-solid fa-flask"></i> Pending Lab Tests</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Test Requested</th>
                    <th>Result / Findings</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($requests as $req): ?>
                <tr>
                    <td><?= $req['patient_name'] ?> <br><small><?= $req['student_id'] ?></small></td>
                    <td><b style="color:var(--lab)"><?= $req['test_name'] ?></b></td>
                    <form action="submit_lab_result.php" method="POST">
                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                        <input type="hidden" name="apt_id" value="<?= $req['appointment_id'] ?>">
                        <td><input type="text" name="results" placeholder="Enter values..." required></td>
                        <td><button type="submit" class="btn-send">Send to Doctor</button></td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>