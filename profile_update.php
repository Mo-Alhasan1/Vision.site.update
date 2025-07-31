<?php
session_start();
require_once 'db.php'; // your PDO connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Helper function to generate unique file name
function generateFilename($originalName) {
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    return uniqid('avatar_', true) . '.' . $ext;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize & validate inputs
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $birth_date = $_POST['birth_date'] ?? null;
    $gender = $_POST['gender'] ?? '';
    $phone_prefix = trim($_POST['phone_prefix'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');

    if (!$first_name || !$last_name || !$email) {
        $_SESSION['profile_message'] = "First name, last name, and email are required.";
        header("Location: profile.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['profile_message'] = "Invalid email address.";
        header("Location: profile.php");
        exit();
    }

    // Check if email is already used by another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
    $stmt->execute(['email' => $email, 'id' => $userId]);
    if ($stmt->fetch()) {
        $_SESSION['profile_message'] = "Email is already in use by another account.";
        header("Location: profile.php");
        exit();
    }

    // Handle avatar upload if file was submitted
    $avatarFileName = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $avatar = $_FILES['avatar'];

        // Basic checks
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($avatar['type'], $allowedTypes)) {
            $_SESSION['profile_message'] = "Avatar must be JPG, PNG, or GIF.";
            header("Location: profile.php");
            exit();
        }
        if ($avatar['size'] > 2 * 1024 * 1024) { // 2MB max
            $_SESSION['profile_message'] = "Avatar image size must be less than 2MB.";
            header("Location: profile.php");
            exit();
        }

        // Save avatar
        $uploadDir = __DIR__ . '/uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newFileName = generateFilename($avatar['name']);
        $destination = $uploadDir . $newFileName;

        if (!move_uploaded_file($avatar['tmp_name'], $destination)) {
            $_SESSION['profile_message'] = "Failed to upload avatar.";
            header("Location: profile.php");
            exit();
        }

        $avatarFileName = $newFileName;

        // Delete old avatar if exists
        $stmt = $pdo->prepare("SELECT avatar FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $oldAvatar = $stmt->fetchColumn();
        if ($oldAvatar && file_exists($uploadDir . $oldAvatar)) {
            unlink($uploadDir . $oldAvatar);
        }
    }

    // Update database
    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, birth_date = :birth_date, gender = :gender, phone_prefix = :phone_prefix, phone_number = :phone_number";
    if ($avatarFileName !== null) {
        $sql .= ", avatar = :avatar";
    }
    $sql .= " WHERE id = :id";

    $params = [
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':email' => $email,
        ':birth_date' => $birth_date,
        ':gender' => $gender,
        ':phone_prefix' => $phone_prefix,
        ':phone_number' => $phone_number,
        ':id' => $userId,
    ];
    if ($avatarFileName !== null) {
        $params[':avatar'] = $avatarFileName;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $_SESSION['profile_message'] = "Profile updated successfully.";
    header("Location: profile.php");
    exit();
} else {
    // Not a POST request
    header("Location: profile.php");
    exit();
}
