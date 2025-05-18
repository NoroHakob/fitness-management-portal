<?php
require_once('db_connect.php');
session_start();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$result = pg_query_params($db, "SELECT * FROM users WHERE email = $1", [$email]);

if (!$result || pg_num_rows($result) === 0) {
  die("No user found.");
}

$user = pg_fetch_assoc($result);

if (!password_verify($password, $user['password_hash'])) {
  die("Incorrect password.");
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['email'] = $user['email'];

header('Location: ../public/index.php');
exit;
