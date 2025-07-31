<?php
require_once '../db.php'; // Adjust path if needed

$email = '*';
$plainPassword = '*';

//visit http://localhost/vision-site/admin/create_admin.php


// Securely hash the password
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Insert the admin into the database
$stmt = $pdo->prepare("INSERT INTO admins (email, password, created_at, last_active) VALUES (?, ?, NOW(), NOW())");
$stmt->execute([$email, $hashedPassword]);

echo "✅ Admin inserted successfully. You can now log in.";
