<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$user_email = htmlspecialchars($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once('../api/meta.php'); ?>
  <meta charset="UTF-8" />
  <title>My Dashboard</title>
  <?php renderMetaTags(
  "Your Fitness Dashboard",
  "Track workouts, manage plans, and monitor your fitness progress.",
  "https://yourdomain.com/assets/images/dashboard-og.jpg",
  "https://yourdomain.com/dashboard.php"
); ?>
  <link rel="canonical" href="<?php echo $og_url ?? 'https://yourdomain.com'; ?>">
  <meta name="robots" content="index, follow">
  <meta name="author" content="Fitness App Team">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main style="max-width: 800px; margin: 2rem auto;">
    <h1>Welcome, <?php echo $user_email; ?>!</h1>
    <p>This is your fitness Dashboard.</p>

    <section style="margin-top: 2rem;">
      <ul style="list-style: none; padding: 0;">
        <li><a href="plans.php" style="font-size: 1.1rem;">📝 View & Manage Workout Plans</a></li>
        <li><a href="progress.php" style="font-size: 1.1rem;">📈 Track Progress & Logs</a></li>
        <li><a href="settings.php" style="font-size: 1.1rem;">⚙️ Account Settings</a></li>
        <li><a href="create_plan.php">➕ Create New Plan</a></li>
        <li><a href="log_workout.php">🏋️ Log Workout</a></li>
        <li><a href="progress.php">📈 View Progress</a></li>
      </ul>
    </section>
  </main>
  <script src="script.js" defer></script>
</body>
</html>