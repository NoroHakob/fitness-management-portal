<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
require_once('../api/db_connect.php');

$result = pg_query($db, "SELECT id, name FROM exercises ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create Workout Plan</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main style="max-width: 700px; margin: 2rem auto;">
    <h1>Create a Custom Workout Plan</h1>
    <form method="POST" action="../api/save_plan.php">
      <label>Plan Name:<br>
        <input type="text" name="plan_name" required>
      </label><br><br>

      <label>Select Exercises:<br>
        <select name="exercise_ids[]" multiple size="10" required style="width: 100%;">
          <?php while ($row = pg_fetch_assoc($result)): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
          <?php endwhile; ?>
        </select>
      </label><br><br>

      <button type="submit">Save Plan</button>
    </form>
  </main>
  <script src="script.js" defer></script>
</body>
</html>