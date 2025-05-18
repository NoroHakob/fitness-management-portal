<?php
session_start();
require_once('db_connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
  die("Unauthorized.");
}

$user_id = $_SESSION['user_id'];
$plan_name = trim($_POST['plan_name'] ?? '');
$exercise_ids = $_POST['exercise_ids'] ?? [];
$plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : null;

if ($plan_name === '' || !is_array($exercise_ids) || count($exercise_ids) === 0) {
  die("Plan name and at least one exercise are required.");
}

if ($plan_id) {
  // 🛠 UPDATE existing plan
  $check = pg_query_params($db, "SELECT id FROM workout_plans WHERE id = $1 AND user_id = $2", [$plan_id, $user_id]);
  if (!$check || pg_num_rows($check) === 0) {
    die("Unauthorized.");
  }

  pg_query_params($db, "UPDATE workout_plans SET name = $1 WHERE id = $2", [$plan_name, $plan_id]);
  pg_query_params($db, "DELETE FROM plan_exercises WHERE plan_id = $1", [$plan_id]);

  foreach ($exercise_ids as $ex_id) {
    $ex_id = intval($ex_id);
    pg_query_params($db,
      "INSERT INTO plan_exercises (plan_id, exercise_id) VALUES ($1, $2)",
      [$plan_id, $ex_id]
    );
  }

  header("Location: ../public/plans.php");
  exit;
} else {
  // ➕ CREATE new plan
  $result = pg_query_params($db,
    "INSERT INTO workout_plans (user_id, name) VALUES ($1, $2) RETURNING id",
    [$user_id, $plan_name]
  );

  if (!$result) {
    die("Failed to save plan.");
  }

  $plan = pg_fetch_assoc($result);
  $new_plan_id = $plan['id'];

  foreach ($exercise_ids as $ex_id) {
    $ex_id = intval($ex_id);
    pg_query_params($db,
      "INSERT INTO plan_exercises (plan_id, exercise_id) VALUES ($1, $2)",
      [$new_plan_id, $ex_id]
    );
  }

  header("Location: ../public/dashboard.php");
  exit;
}
