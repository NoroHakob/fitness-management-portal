<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

require_once('../api/db_connect.php');
$user_id = $_SESSION['user_id'];

$result = pg_query_params($db,
  "SELECT id, name, created_at FROM workout_plans WHERE user_id = $1 ORDER BY created_at DESC",
  [$user_id]
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Workout Plans</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main style="max-width: 800px; margin: 2rem auto;">
    <h1>My Workout Plans</h1>
    <a href="create_plan.php">➕ Create New Plan</a><br><br>

    <?php if (pg_num_rows($result) === 0): ?>
      <p>You haven't created any plans yet.</p>
    <?php else: ?>
      <ul>
        <?php while ($row = pg_fetch_assoc($result)): ?>
          <li>
            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
            <small>(<?php echo date('M j, Y', strtotime($row['created_at'])); ?>)</small><br>
            <a href="edit_plan.php?id=<?php echo $row['id']; ?>">✏️ Edit</a> |
            <a href="../api/delete_plan.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this plan?');">🗑️ Delete</a>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php endif; ?>
  </main>
  <script src="script.js" defer></script>
</body>
</html>
