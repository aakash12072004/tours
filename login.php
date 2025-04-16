<?php
$conn = new mysqli("localhost", "root", "", "travel_db");

$loginMessage = '';
$registerMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows == 1) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $loginMessage = "✅ Login successful! Welcome back.";
                
                // Redirect to homepage after successful login
                header("Location: index.php"); // Replace 'homepage.php' with your homepage URL
                exit();
            } else {
                $loginMessage = "❌ Incorrect password.";
            }
        } else {
            $loginMessage = "❌ No account found with that email.";
        }
    }

    if (isset($_POST['register'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $registerMessage = "❌ Email is already registered.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $fullname, $email, $password);
            if ($stmt->execute()) {
                $registerMessage = "✅ Registration successful! You can log in now.";
            } else {
                $registerMessage = "❌ Registration failed: " . $stmt->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Globe Trekker - Login & Registration</title>
  <link rel="stylesheet" href="loginstyle.css">
  <style>
    .message {
      margin: 10px 0;
      font-weight: bold;
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 id="form-title">Login</h2>

    <!-- Login Form -->
    <form id="login-form" method="POST">
      <div class="message"><?= $loginMessage ?></div>
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required>
        <span>📧</span>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Password" required>
        <span>🔒</span>
      </div>
      <button class="btn" type="submit" name="login">Login</button>
    </form>

    <!-- Register Form -->
    <form id="register-form" class="hidden" method="POST">
      <div class="message"><?= $registerMessage ?></div>
      <div class="input-group">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <span>👤</span>
      </div>
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required>
        <span>📧</span>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Password" required>
        <span>🔒</span>
      </div>
      <button class="btn register" type="submit" name="register">Register</button>
    </form>

    <p class="toggle-text">
      <span id="toggle-text">Don't have an account?</span>
      <button id="toggle-btn" type="button">Register</button>
    </p>
  </div>

  <script>
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const formTitle = document.getElementById("form-title");
    const toggleText = document.getElementById("toggle-text");
    const toggleBtn = document.getElementById("toggle-btn");

    toggleBtn.addEventListener("click", () => {
      const isLogin = !loginForm.classList.contains("hidden");
      loginForm.classList.toggle("hidden");
      registerForm.classList.toggle("hidden");
      formTitle.textContent = isLogin ? "Register" : "Login";
      toggleText.textContent = isLogin
        ? "Already have an account?"
        : "Don't have an account?";
      toggleBtn.textContent = isLogin ? "Login" : "Register";
    });
  </script>
</body>
</html>
