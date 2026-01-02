<?php
session_start();
require_once('../includes/db.php'); // Ensure this path is correct!
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
        exit;
    }

    try {
        // Find user by username (case-insensitive)
        $stmt = $conn->prepare("SELECT * FROM users WHERE LOWER(username) = LOWER(?) LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // THE CRITICAL CHECK:
            // Check if the password matches a HASH (secure) OR PLAIN TEXT (development)
            $isHashMatch = password_verify($password, $user['password']);
            $isPlainMatch = ($password === $user['password']);

            if ($isHashMatch || $isPlainMatch) {
                // SUCCESS: Set the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = strtolower($user['role']); 
                $_SESSION['full_name'] = $user['full_name'];

                echo json_encode([
                    "success" => true, 
                    "role" => $_SESSION['role']
                ]);
                exit;
            }
        }

        // If we get here, it failed
        echo json_encode(["success" => false, "message" => "Invalid ID Number or Password."]);

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database Error: " . $e->getMessage()]);
    }
}