<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password_plain = $_POST['password'];

    $sql = "SELECT id, password FROM account WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $password_hash);

    if ($stmt->fetch()) {
        if (password_verify($password_plain, $password_hash)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - PEMILU IKA POLBAN</title>
<link rel="stylesheet" href="style.css">
<style>
.auth-container {
    max-width: 400px;
    margin: 80px auto;
    background: white;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
}
.input-group {
    position: relative;
}
.input-group input {
    width: 100%;
    padding: 10px 35px 10px 10px;
    margin: 8px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.toggle-password {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
}
.error { color: red; text-align: center; margin-bottom: 10px; }
</style>
</head>
<body>

<header class="header-container">
    <div class="logo-container">
        <a href="index.html"><img src="images/logo POKJA.png" alt="Logo 1" class="logo"></a>
        <a href="index.html"><img src="images/logo IKA POLBAN.png" alt="Logo 2" class="logo"></a>
    </div>
    <h1 class="main-title">PEMILU IKA POLBAN 2025</h1>
</header>

<div class="auth-container">
    <h2 style="text-align:center">Login</h2>
    <?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
            <span class="toggle-password">👁</span>
        </div>
        <button type="submit" class="btn" style="width:100%">Masuk</button>
    </form>
    <p style="text-align:center; margin-top:10px;">Belum punya akun? <a href="register.php">Daftar</a></p>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(el => {
    el.addEventListener('click', () => {
        let input = el.previousElementSibling;
        if (input.type === "password") {
            input.type = "text"; el.textContent = "🙈";
        } else {
            input.type = "password"; el.textContent = "👁";
        }
    });
});
</script>
</body>
</html>
