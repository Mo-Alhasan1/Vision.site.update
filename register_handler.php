<?php
session_start();
require_once 'db.php';  // your PDO connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize all fields
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $birth_date   = $_POST['birth_date'] ?? '';
    $street      = trim($_POST['street'] ?? '');
    $city        = trim($_POST['city'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');
    $country     = trim($_POST['country'] ?? '');
    $phone_prefix = trim($_POST['prefix'] ?? '');
    $phone_number = trim($_POST['phone'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $password    = $_POST['password'] ?? '';
    $newsletter  = !empty($_POST['newsletter']) ? 1 : 0;
    $privacy     = !empty($_POST['privacy']);

    // Basic validation for required fields
    if (
        !$first_name || !$last_name || !$birth_date || !$street || !$city || !$postal_code || !$country
        || !$phone_prefix || !$phone_number || !$email || !$password || !$privacy
    ) {
        $_SESSION['register_error'] = "Please fill in all required fields and accept the privacy statement.";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Please enter a valid email address.";
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['register_error'] = "Password must be at least 6 characters.";
        header("Location: register.php");
        exit();
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $_SESSION['register_error'] = "This email is already registered.";
            header("Location: register.php");
            exit();
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert all data into the users table
        $stmt = $pdo->prepare("INSERT INTO users 
            (first_name, last_name, birth_date, street, city, postal_code, country, phone_prefix, phone_number, email, password, newsletter, created_at) 
            VALUES 
            (:first_name, :last_name, :birth_date, :street, :city, :postal_code, :country, :phone_prefix, :phone_number, :email, :password, :newsletter, NOW())");

        $stmt->execute([
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'birth_date'   => $birth_date,
            'street'       => $street,
            'city'         => $city,
            'postal_code'  => $postal_code,
            'country'      => $country,
            'phone_prefix' => $phone_prefix,
            'phone_number' => $phone_number,
            'email'        => $email,
            'password'     => $passwordHash,
            'newsletter'   => $newsletter,
        ]);

        $_SESSION['register_success'] = "Registration successful! You can now log in.";
        header("Location: login.php");
        exit();
    } catch (Exception $e) {
        error_log("Register error: " . $e->getMessage());
        $_SESSION['register_error'] = "An error occurred. Please try again.";
        header("Location: register.php");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
