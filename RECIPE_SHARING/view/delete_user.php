<?php
session_start();
include('config.php');

// Ensure user is Super Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'super_admin') {
    header("Location: login.html");
    exit("Unauthorized access.");
}

// Delete user only if user_id is valid
if (isset($_POST['user_id'])) {
    $userId = filter_var($_POST['user_id'], FILTER_VALIDATE_INT);
    if (!$userId) {
        exit("Invalid user ID.");
    }

    // Delete user by ID with error handling
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
    } catch (PDOException $e) {
        exit("Error deleting user: " . htmlspecialchars($e->getMessage()));
    }

    // Redirect back to users.php with a success message
    header("Location: users.php?delete_success=1");
    exit;
} else {
    exit("No user ID provided.");
}
?>
