<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require_once('../api/db_connect.php');
$user_id = $_SESSION['user_id'];

$plan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Verify plan belongs to user
$plan = pg_query_params($db,
  "SELECT * FROM workout_plans WHERE id = $1 AND user_id = $2",
  [$plan_id, $user_id]
);

if (!$plan || pg_num_rows($plan) === 0) {
  die("Plan not found or unauthorized.");
}
$plan = pg_fetch_assoc($plan);

// Fetch all exercises
$all_exercises = pg_query($db, "SELECT id, name FROM exercises ORDER BY name ASC");

// Fetch selected exercises for this plan
$selected_ids = [];
$res = pg_query_params($db, "SELECT exercise_id FROM plan_exercises WHERE plan_id = $1", [$plan_id]);
while ($row = pg_fetch_assoc($res)) {
  $selected_ids[] = $row['exercise_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Plan</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main style="max-width: 700px; margin: 2rem auto;">
    <h1>Edit Workout Plan</h1>
    <form method="POST" action="../api/save_plan.php">
      <input type="hidden" name="plan_id" value="<?php echo $plan_id; ?>">
      <label>Plan Name:<br>
        <input type="text" name="plan_name" required value="<?php echo htmlspecialchars($plan['name']); ?>">
      </label><br><br>

      <label>Select Exercises:<br>
        <select name="exercise_ids[]" multiple size="10" required style="width: 100%;">
          <?php while ($ex = pg_fetch_assoc($all_exercises)): ?>
            <?php $selected = in_array($ex['id'], $selected_ids) ? 'selected' : ''; ?>
            <option value="<?php echo $ex['id']; ?>" <?php echo $selected; ?>>
              <?php echo htmlspecialchars($ex['name']); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </label><br><br>

      <button type="submit">Update Plan</button>
    </form>
  </main>
  <script src="script.js" defer></script>
</body>
</html>
