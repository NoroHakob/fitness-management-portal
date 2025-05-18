<?php
session_start();
require_once('db_connect.php');

if (!isset($_SESSION['user_id'])) {
  die("Unauthorized.");
}

$user_id = $_SESSION['user_id'];
$plan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ensure ownership before delete
$result = pg_query_params($db,
  "DELETE FROM workout_plans WHERE id = $1 AND user_id = $2",
  [$plan_id, $user_id]
);

header("Location: ../public/plans.php");
exit;
