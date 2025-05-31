<?php
session_start();
include '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        header("Location: ../../index2.php"); // Ganti ke halaman landing sesuai punyamu
        exit();
    } else {
        $error = "Data yang Anda masukkan salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mydonate</title>
    <style>
        * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Arial, sans-serif;
}

body {
  background: url(bg4.png) no-repeat center center/cover !important;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.container {
  background: rgba(255, 255, 255, 0.3);
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(5px);
  width: 400px;
  height: 350px;
  text-align: center;
}

.login-box h2 {
  margin-top: 15px;
  margin-bottom: 20px;
}

label {
  display: block;
  text-align: left;
  margin: 10px 0 5px;
  font-weight: bold;
}

.form-label {
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
  font-size: 14px;
  font-weight: lighter;
}

input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 10px;
}

.form-input-password {
  margin-right: 100px;
  margin-bottom: 20px;
}

.password-container {
  position: relative;
}

.password-container input {
  width: calc(100% - 30px);
}

.toggle-password {
  position: absolute;
  right: 1px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
}

.login-btn {
  width: 100%;
  background: #007bff;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.login-btn:hover {
  background: #0056b3;
}

.forgot {
  float: right;
  font-size: 12px;
  color: #007bff;
  text-decoration: none;
  margin-top: 7px;
  margin-right: 25px;
}

.signup-text {
  margin-top: 25px;
  font-size: 14px;
}

.signup-text a {
  color: #007bff;
  text-decoration: none;
  font-weight: bold;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Login User</h2>
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            <form method="POST" action="#">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input-email" placeholder="Enter your email" required>
                
                <div class="password-container">
                    <label for="password" class="form-label">Password <a href="#" class="forgot">Forgot?</a></label>
                    <input type="password" id="password" name="password" class="form-input-password" placeholder="Enter your password" required>
                    <span id="togglePassword" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>
            <p class="signup-text">Don't Have An Account? <a href="SignupUser.php">Sign Up</a></p>
        </div>
    </div>
    <script>
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('password');

      togglePassword.addEventListener('click', function () {
      // Toggle type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);

      // Toggle icon (optional)
      this.textContent = type === 'password' ? '👁️' : '🙈';
      });
    </script>

</body>
</html>