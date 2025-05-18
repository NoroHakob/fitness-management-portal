<?php require_once('../api/db_connect.php'); ?>
<?php session_start(); ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once('../api/meta.php'); ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php renderMetaTags(
    "Fitness App – Bodyweight Exercises & Plans",
    "Explore beginner-friendly bodyweight workouts. Create custom plans, track progress, and get fit using your own bodyweight.",
    "https://yourdomain.com/assets/images/og-home.jpg",
    "https://yourdomain.com/index.php"
  ); ?>
  <link rel="canonical" href="<?php echo $og_url ?? 'https://yourdomain.com'; ?>">
  <meta name="robots" content="index, follow">
  <meta name="author" content="Fitness App Team">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head>

<body>  
  <div class="page-wrapper">
  <header>
    <nav>
      <h1>Fitness Tracker</h1>
      <ul>
        <li><a href="#">Exercises</a></li>
        <li><a href="#">My Plans</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  </div>

  <main>
    <section id="exercise-feed">
      <?php
        $query = "SELECT * FROM exercises ORDER BY id ASC";
        $result = pg_query($db, $query);

        if (!$result) {
          echo "<p>Failed to load exercises.</p>";
        } else {
          while ($row = pg_fetch_assoc($result)) {
            // Escape values to prevent XSS
            $name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
            $category = htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8');
            $difficulty = htmlspecialchars($row['difficulty'], ENT_QUOTES, 'UTF-8');
            $desc = htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8');
            $img = htmlspecialchars($row['image_path'], ENT_QUOTES, 'UTF-8');

            echo "
            <a href='exercise.php?id={$row['id']}' class='card-link'>
              <article class='exercise-card'>
                <img src='../$img' alt='$name' loading='lazy'>
                <h2>$name</h2>
                <p>Category: $category</p>
                <p>Difficulty: $difficulty</p>
                <p>$desc</p>
              </article>
            </a>
            ";
          }
        }
        ?>
    </section>
    
    <footer>
      <p>&copy; 2025 Fitness App. All rights reserved.</p>
    </footer>
  </main>
  <script src="script.js" defer></script>
</body>
</html>
