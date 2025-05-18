<?php
session_start();
require_once('db_connect.php');

if (!isset($_SESSION['user_id'])) {
  die("Unauthorized.");
}

$user_id = $_SESSION['user_id'];
$plan_id = intval($_POST['plan_id'] ?? 0);
$exercise_id = intval($_POST['exercise_id'] ?? 0);
$reps = intval($_POST['reps'] ?? 0);
$log_date = $_POST['log_date'] ?? date('Y-m-d');

if ($plan_id <= 0 || $exercise_id <= 0 || $reps <= 0) {
  die("Invalid input.");
}

$check = pg_query_params($db, "SELECT id FROM workout_plans WHERE id = $1 AND user_id = $2", [$plan_id, $user_id]);
if (!$check || pg_num_rows($check) === 0) {
  die("Invalid plan or unauthorized.");
}

$result = pg_query_params($db,
  "INSERT INTO workout_logs (user_id, plan_id, exercise_id, reps, log_date) VALUES ($1, $2, $3, $4, $5)",
  [$user_id, $plan_id, $exercise_id, $reps, $log_date]
);

if ($result) {
  header("Location: ../public/progress.php");
  exit;
} else {
  die("Error saving log.");
}