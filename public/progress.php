<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
require_once('../api/db_connect.php');
$user_id = $_SESSION['user_id'];

$query = "
SELECT wl.log_date, wl.reps, e.name AS exercise, wp.name AS plan
FROM workout_logs wl
JOIN exercises e ON wl.exercise_id = e.id
JOIN workout_plans wp ON wl.plan_id = wp.id
WHERE wl.user_id = $1
ORDER BY wl.log_date DESC
";
$result = pg_query_params($db, $query, [$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Workout History</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main style="max-width: 700px; margin: 2rem auto;">
    <h1>Workout History</h1>
    <?php if (pg_num_rows($result) === 0): ?>
      <p>No workouts logged yet.</p>
    <?php else: ?>
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr>
            <th>Date</th>
            <th>Plan</th>
            <th>Exercise</th>
            <th>Reps</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = pg_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['log_date']); ?></td>
              <td><?php echo htmlspecialchars($row['plan']); ?></td>
              <td><?php echo htmlspecialchars($row['exercise']); ?></td>
              <td><?php echo htmlspecialchars($row['reps']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>
  <script src="script.js" defer></script>
</body>
</html>
