<?php

function loadEnv($path) {
  if (!file_exists($path)) return;
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    list($key, $value) = explode('=', $line, 2);
    putenv(trim($key) . '=' . trim($value));
  }
}

// Load environment variables
loadEnv(__DIR__ . '/../.env');

// Read values
$db_host = getenv('DB_HOST');
$db_port = getenv('DB_PORT');
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASSWORD');

// Build connection string
$conn_str = "host=$db_host port=$db_port dbname=$db_name user=$db_user password=$db_pass";

// Connect to PostgreSQL
$db = pg_connect($conn_str);

// Check connection
if (!$db) {
  die("Connection failed: " . pg_last_error());
}

$result = pg_query($db, "SELECT name FROM exercises LIMIT 5");

if (!$result) {
  die("Query failed: " . pg_last_error());
}

echo "<h3>Test Query Success:</h3>";
while ($row = pg_fetch_assoc($result)) {
  echo "<p>" . htmlspecialchars($row['name']) . "</p>";
}
