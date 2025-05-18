<?php
// show all errors in development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// load your .env
function loadEnv($path) {
  if (!file_exists($path)) return;
  foreach (file($path, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#') continue;
    if (strpos($line, '=') === false) continue;
    list($key, $value) = explode('=', $line, 2);
    $value = trim($value);
    // strip quotes
    $value = preg_replace('/^"(.*)"$/', '$1', $value);
    putenv("$key=$value");
    $_ENV[$key] = $value;
  }
}
loadEnv(__DIR__ . '/../.env');

// build connection string
$conn_str = sprintf(
  'host=%s port=%s dbname=%s user=%s password=%s sslmode=require',
  getenv('DB_HOST'),
  getenv('DB_PORT'),
  getenv('DB_NAME'),
  getenv('DB_USER'),
  getenv('DB_PASSWORD')
);

// connect
$db = pg_connect($conn_str);
if (!$db) {
  die('Connection failed: ' . pg_last_error($db));
}

// query
$result = pg_query($db, 'SELECT name FROM exercises LIMIT 0');
if (!$result) {
  die('Query failed: ' . pg_last_error($db));
}

// output 
while ($row = pg_fetch_assoc($result)) {
  echo '<p>' . htmlspecialchars($row['name'], ENT_QUOTES) . '</p>';
}
