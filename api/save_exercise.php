<?php
require_once('db_connect.php');

$name = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$difficulty = trim($_POST['difficulty'] ?? '');
$description = trim($_POST['description'] ?? '');
$image_path = trim($_POST['image_path'] ?? '');
$id = isset($_POST['id']) ? intval($_POST['id']) : null;

$errors = [];
if ($name === '' || $category === '' || $difficulty === '' || $description === '') {
  $errors[] = "All fields except image are required.";
}
if (!in_array($difficulty, ['Beginner', 'Intermediate', 'Advanced'])) {
  $errors[] = "Invalid difficulty.";
}

if (!empty($errors)) {
  echo "<h3>Form Error:</h3><ul>";
  foreach ($errors as $e) echo "<li>$e</li>";
  echo "</ul><a href='../public/admin_form.php" . ($id ? "?id=$id" : "") . "'>← Go back</a>";
  exit;
}

if ($id) {
  $query = "UPDATE exercises SET name=$1, category=$2, difficulty=$3, description=$4, image_path=$5 WHERE id=$6";
  $params = [$name, $category, $difficulty, $description, $image_path, $id];
} else {
  $query = "INSERT INTO exercises (name, category, difficulty, description, image_path) VALUES ($1, $2, $3, $4, $5)";
  $params = [$name, $category, $difficulty, $description, $image_path];
}

$result = pg_query_params($db, $query, $params);

if ($result) {
  header('Location: ../public/index.php');
  exit;
} else {
  echo "<h3>Database Error</h3><p>" . pg_last_error($db) . "</p>";
}