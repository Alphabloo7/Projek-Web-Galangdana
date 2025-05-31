<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $no_telepon = $_POST['no_telepon'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $password = $_POST['password'] ?? '';
    $verify = $_POST['verify-password'] ?? '';

    if ($password !== $verify) {
        $error = "Password tidak sama!";
    } else {
        // Cek apakah email sudah terdaftar
        $check_email = "SELECT * FROM user WHERE email = ?";
        $stmt_check = $koneksi->prepare($check_email);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $bergabung = date('Y-m-d');
            $status = "aktif";

            // Insert user baru
            $sql = "INSERT INTO user (nama, email, password, no_telepon, alamat, username, bergabung_user, status_user)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("ssssssss", $nama, $email, $hashed_password, $no_telepon, $alamat, $username, $bergabung, $status);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header("Location: Login.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Mydonate</title>
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
  width: 500px;
  height: 700px;
  background-color: rgba(209, 207, 207, 0.3);
  backdrop-filter: blur(5px);
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
  border-radius: 25px;
  padding: 20px;
  color: #000000;
  max-width: 500px;
  display: inline-block;
  box-sizing: border-box;
  position: relative;
}

.content h2 {
  text-align: center;
  color: #030303;
  font-size: 32px;
  margin-top: 15px;
  margin-bottom: 30px;
}

.button-user {
  position: absolute;
  background-color: rgb(255, 255, 255);
  border: none;
  color: #000000;
  width: 190px;
  height: 50px;
  display: inline-block;
  border-radius: 10px;
  margin-left: 30px;
  margin-bottom: 25px;
  justify-content: left;
  font-weight: bold;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
}

.button-yayasan {
  position: flex;
  background-color: black;
  border: none;
  color: #ffffff;
  width: 190px;
  height: 50px;
  margin-left: 240px;
  margin-bottom: 25px;
  display: inline-block;
  border-radius: 10px;
  font-weight: bold;
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
}

.active-1 {
  text-decoration: none;
  color: #000000;
}

.active-2 {
  text-decoration: none;
  color: #ffffff;
}

.form-label {
  font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
  font-size: 14px;
  font-weight: lighter;
  margin-left: 30px;
}

.form-input-nama {
  width: 88%;
  padding: 8px;
  background-color: rgba(209, 207, 207, 0.1);
  border: 3px solid #050505;
  backdrop-filter: blur (5px);
  border-radius: 5px;
  margin-top: 5px;
  margin-bottom: 10px;
  margin-left: 30px;
  border: 2px solid #050505;
}

.form-input-username {
  width: 88%;
  padding: 8px;
  background-color: rgba(209, 207, 207, 0.1);
  border: 3px solid #050505;
  backdrop-filter: blur (5px);
  border-radius: 5px;
  margin-top: 5px;
  margin-bottom: 10px;
  margin-left: 30px;
  border: 2px solid #050505;
}

.form-input-email {
  width: 88%;
  padding: 8px;
  background-color: rgba(209, 207, 207, 0.1);
  border: 3px solid #050505;
  backdrop-filter: blur (5px);
  border-radius: 5px;
  margin-top: 5px;
  margin-bottom: 10px;
  margin-left: 30px;
  border: 2px solid #050505;
}

.form-input-password {
  width: 88%;
  padding: 8px;
  background-color: rgba(209, 207, 207, 0.1);
  border: 3px solid #050505;
  backdrop-filter: blur (5px);
  border-radius: 5px;
  margin-top: 5px;
  margin-bottom: 10px;
  margin-left: 30px;
  border: 2px solid #050505;
}

.submit-btn {
  width: 88%;
  background: #007bff;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  margin-left: 30px;
  margin-top: 20px;
}

.submit-btn:hover {
  background: #0056b3;
}

    </style>    
</head>
<body>
    <div class="container">
        <div class="content">
            <h2>Create An Account</h2>
            <div class="user">
                <button type="submit" class="button-user"><a href="SignupUser.php" class="active-1">User</a></button>
            </div>
            <div class="yayasan">
                <button type="submit" class="button-yayasan"><a href="SignupYayasan.php" class="active-2">Yayasan</a></button>
            </div>
            <form action="#" method="post">
              <div class="part-form">
                  <label for="nama" class="form-label">Nama</label>
                  <input type="text" name="nama" id="nama" class="form-input-nama" placeholder="Your name" required>
              </div>
              <div class="part-form">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" name="username" id="username" class="form-input-username" placeholder="Your Username" required>
              </div>
              <div class="part-form">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" name="email" id="email" class="form-input-email" placeholder="Enter Your Email" required>
              </div>
              <div class="part-form">
                  <label for="no_telepon" class="form-label">Nomor Telepon</label>
                  <input type="text" name="no_telepon" id="no_telepon" class="form-input-nama" placeholder="Enter Your Phone Number" required>
              </div>
              <div class="part-form">
                  <label for="alamat" class="form-label">Alamat</label>
                  <input type="text" name="alamat" id="alamat" class="form-input-nama" placeholder="Enter Your Address" required>
              </div>
              <div class="part-form">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" id="password" class="form-input-password" placeholder="Enter Your Password" required>
              </div>
              <div class="part-form">
                  <label for="verify" class="form-label">Verify Password</label>
                  <input type="password" name="verify-password" id="verify" class="form-input-password" placeholder="Enter Your Password" required>
              </div>
              <button type="submit" class="submit-btn">Create</button>
          </form>

        </div>
    </div>
</body>
</html>