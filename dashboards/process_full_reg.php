<?php
require_once('../includes/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $student_id_code = $_POST['username']; 
    $phone = $_POST['phone'];
    $dept = $_POST['department'];
    $blood = $_POST['blood_group'];
    $default_password = password_hash('student123', PASSWORD_DEFAULT);

    try {
        $conn->beginTransaction();

        // 1. Check if the ID already exists
        $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$student_id_code]);
        if ($check->rowCount() > 0) {
            throw new Exception("This Student ID is already registered.");
        }

        // 2. Insert into 'users' table (for login)
        // Adding phone/dept/blood here as well if your users table has those columns
        $stmt1 = $conn->prepare("INSERT INTO users (full_name, username, password, phone, department, blood_group, role) VALUES (?, ?, ?, ?, ?, ?, 'student')");
        $stmt1->execute([$name, $student_id_code, $default_password, $phone, $dept, $blood]);
        
        $new_user_id = $conn->lastInsertId();

        // 3. Insert into 'students' table (for records)
        $stmt2 = $conn->prepare("INSERT INTO students (user_id, student_id_code, full_name, phone, department, blood_group) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->execute([$new_user_id, $student_id_code, $name, $phone, $dept, $blood]);

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Student Registered Successfully']);

    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>