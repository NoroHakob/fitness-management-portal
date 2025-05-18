<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <main style="max-width: 400px; margin: 2rem auto;">
    <h1>Register</h1>
    <form method="POST" action="../api/auth_register.php">
      <label>Email:<br>
        <input type="email" name="email" required>
      </label><br><br>
      <label>Password:<br>
        <input type="password" name="password" required minlength="6">
      </label><br><br>
      <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
  </main>
  <script src="script.js" defer></script>
</body>
</html>
