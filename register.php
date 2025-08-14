<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password_plain = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if ($password_plain !== $password_confirm) {
        $error = "Password dan konfirmasi tidak sama!";
    } else {
        $check = $conn->prepare("SELECT id FROM account WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO account (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password_hash);
            if ($stmt->execute()) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Gagal registrasi.";
            }
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar - PEMILU IKA POLBAN</title>
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
.success { color: green; text-align: center; margin-bottom: 10px; }
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
    <h2 style="text-align:center">Registrasi</h2>
    <?php if (!empty($error)): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
            <span class="toggle-password">👁</span>
        </div>
        <div class="input-group">
            <input type="password" name="password_confirm" placeholder="Konfirmasi Password" required>
            <span class="toggle-password">👁</span>
        </div>
        <button type="submit" class="btn" style="width:100%">Daftar</button>
    </form>
    <p style="text-align:center; margin-top:10px;">Sudah punya akun? <a href="login.php">Login</a></p>
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
