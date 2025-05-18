<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main style="max-width: 400px; margin: 2rem auto;">
    <h1>Login</h1>
    <form method="POST" action="../api/auth_login.php">
      <label>Email:<br>
        <input type="email" name="email" required>
      </label><br><br>
      <label>Password:<br>
        <input type="password" name="password" required>
      </label><br><br>
      <button type="submit">Login</button>
    </form>
    <p>New here? <a href="register.php">Register now</a>.</p>
  </main>
  <script src="script.js" defer></script>
</body>
</html>
