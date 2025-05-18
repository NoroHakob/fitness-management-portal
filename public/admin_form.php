<?php
require_once('../api/db_connect.php');

$editing = false;
$exercise = [
  'id' => '',
  'name' => '',
  'category' => '',
  'difficulty' => '',
  'description' => '',
  'image_path' => ''
];

// Check for edit mode
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $id = intval($_GET['id']);
  $result = pg_query_params($db, "SELECT * FROM exercises WHERE id = $1", [$id]);
  if ($result && pg_num_rows($result) === 1) {
    $exercise = pg_fetch_assoc($result);
    $editing = true;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $editing ? 'Edit' : 'Add'; ?> Exercise</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main style="max-width: 600px; margin: 2rem auto;">
    <h1><?php echo $editing ? 'Edit' : 'Add New'; ?> Exercise</h1>

    <form action="../api/save_exercise.php" method="POST">
      <?php if ($editing): ?>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($exercise['id']); ?>">
      <?php endif; ?>

      <label>Name:<br>
        <input type="text" name="name" required value="<?php echo htmlspecialchars($exercise['name']); ?>">
      </label><br><br>

      <label>Category:<br>
        <input type="text" name="category" required value="<?php echo htmlspecialchars($exercise['category']); ?>">
      </label><br><br>

      <label>Difficulty:<br>
        <select name="difficulty" required>
          <?php
            $levels = ['Beginner', 'Intermediate', 'Advanced'];
            foreach ($levels as $level) {
              $selected = $exercise['difficulty'] === $level ? 'selected' : '';
              echo "<option value='$level' $selected>$level</option>";
            }
          ?>
        </select>
      </label><br><br>

      <label>Description:<br>
        <textarea name="description" rows="4" required><?php echo htmlspecialchars($exercise['description']); ?></textarea>
      </label><br><br>

      <label>Image Path:<br>
        <input type="text" name="image_path" value="<?php echo htmlspecialchars($exercise['image_path']); ?>">
      </label><br><br>

      <button type="submit"><?php echo $editing ? 'Update' : 'Add'; ?> Exercise</button>
    </form>
  </main>
  <script src="script.js" defer></script>
</body>
</html>