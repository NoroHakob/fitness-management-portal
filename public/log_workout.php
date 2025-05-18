<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require_once('../api/db_connect.php');
$user_id = $_SESSION['user_id'];

// Fetch user’s plans
$plans = pg_query_params($db, "SELECT id, name FROM workout_plans WHERE user_id = $1", [$user_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Log a Workout</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main style="max-width: 600px; margin: 2rem auto;">
    <h1>Log a Workout</h1>

    <form method="POST" action="../api/save_log.php">
      <label>Workout Plan:<br>
        <select name="plan_id" required>
          <option value="">-- Select Plan --</option>
          <?php while ($plan = pg_fetch_assoc($plans)): ?>
            <option value="<?php echo $plan['id']; ?>"><?php echo htmlspecialchars($plan['name']); ?></option>
          <?php endwhile; ?>
        </select>
      </label><br><br>

      <label>Exercise ID:<br>
        <input type="number" name="exercise_id" required placeholder="Enter Exercise ID">
      </label><br><br>

      <label>Reps:<br>
        <input type="number" name="reps" required min="1">
      </label><br><br>

      <label>Date:<br>
        <input type="date" name="log_date" value="<?php echo date('Y-m-d'); ?>" required>
      </label><br><br>

      <button type="submit">Log Workout</button>
    </form>
  </main>
  <script src="script.js" defer></script>
</body>
</html>
