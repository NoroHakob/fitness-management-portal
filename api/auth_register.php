<?php
require_once('db_connect.php');

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
  die("Invalid email or password too short.");
}

// Hash the password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$result = pg_query_params($db,
  "INSERT INTO users (email, password_hash) VALUES ($1, $2)",
  [$email, $hash]
);

if ($result) {
  header('Location: ../public/login.php');
  exit;
} else {
  echo "Registration failed: " . pg_last_error($db);
}
