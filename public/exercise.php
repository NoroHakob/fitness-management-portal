<?php
require_once('../api/db_connect.php');

// Validate the incoming ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
  echo "<h2>Invalid exercise ID.</h2>";
  exit;
}

// Use parameterized query to prevent SQL injection
$result = pg_query_params($db, "SELECT * FROM exercises WHERE id = $1", [$id]);

if (!$result || pg_num_rows($result) === 0) {
  echo "<h2>Exercise not found.</h2>";
  exit;
}

$exercise = pg_fetch_assoc($result);

// Escape output for safety
$name = htmlspecialchars($exercise['name']);
$desc = htmlspecialchars($exercise['description']);
$category = htmlspecialchars($exercise['category']);
$difficulty = htmlspecialchars($exercise['difficulty']);
$image = htmlspecialchars($exercise['image_path']);
?>

<?php
require_once('../api/meta.php');

$og_title = $name . " – Bodyweight Exercise";
$og_desc = $desc;
$og_image = "https://yourdomain.com/" . $image;
$og_url = "https://yourdomain.com/exercise.php?id=" . urlencode($exercise['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $name; ?> – Exercise Details</title>
  <?php renderMetaTags($og_title, $og_desc, $og_image, $og_url); ?>
  <link rel="canonical" href="<?php echo $og_url ?? 'https://yourdomain.com'; ?>">
  <meta name="robots" content="index, follow">
  <meta name="author" content="Fitness App Team">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main style="max-width: 800px; margin: 2rem auto;">
    <a href="index.php" style="text-decoration: none; font-size: 0.9rem;">← Back to All Exercises</a>
    <h1 style="margin-top: 1rem;"><?php echo $name; ?></h1>
    <img src="../<?php echo $image; ?>" alt="<?php echo $name; ?>" style="width: 100%; border-radius: 8px; margin: 1rem 0;" loading='lazy'/>
    <p><strong>Category:</strong> <?php echo $category; ?></p>
    <p><strong>Difficulty:</strong> <?php echo $difficulty; ?></p>
    <p style="margin-top: 1rem;"><?php echo $desc; ?></p>
  </main>
  <script src="script.js" defer></script>
</body>
</html>
