<?php
require_once('../includes/db.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['full_name'];
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (full_name, username, password, role) VALUES (?, ?, ?, 'student')");
        $stmt->execute([$name, $user, $pass]);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Student ID already exists!']);
    }
}