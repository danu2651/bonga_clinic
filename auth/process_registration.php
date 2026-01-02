<?php
// 1. Include the unified PDO connection
require_once('../includes/db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // 2. Collect and sanitize data from the form
        $role             = $_POST['role'];
        $id_number        = trim($_POST['id_number']); // Maps to 'username' in DB
        $fullname         = trim($_POST['fullname']);  // Maps to 'full_name' in DB
        $email            = trim($_POST['email']);
        $phone            = trim($_POST['phone']);
        $password         = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // 3. Validation: Check if passwords match
        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
            exit;
        }

        // 4. Hash Password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 5. Securely Insert using Prepared Statements 
        // Note: Column names now match your NEW unified table structure
        $sql = "INSERT INTO users (username, full_name, email, phone, password, role) 
                VALUES (:username, :full_name, :email, :phone, :password, :role)";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute([
            ':username'  => $id_number,
            ':full_name' => $fullname,
            ':email'     => $email,
            ':phone'     => $phone,
            ':password'  => $hashed_password,
            ':role'      => $role
        ]);

        // 6. Success: Redirect to login
        echo "<script>alert('Account Created Successfully! You can now login.'); window.location.href='login.php';</script>";

    } catch (PDOException $e) {
        // 7. Error Handling: Check for duplicate entries (ID or Email)
        if ($e->getCode() == 23000) {
            echo "<script>alert('Error: This ID Number or Email is already registered.'); window.history.back();</script>";
        } else {
            // Display general SQL errors for debugging
            echo "Database Error: " . $e->getMessage();
        }
    }
} else {
    // If someone tries to access this file directly without POST
    header("Location: register.php");
    exit;
}
?>