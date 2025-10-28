<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username === "" || $password === "") {
        $error = "⚠️ Please fill all fields.";
    } else {
        // 检查用户名是否存在
        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "⚠️ Username already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);
            $stmt->execute();
            header("Location: login.php?registered=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<style>
    body { font-family: Poppins, sans-serif; background: #eef3ff; display:flex; justify-content:center; align-items:center; height:100vh; }
    .box { background:#fff; padding:30px; border-radius:15px; width:320px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
    h2 { text-align:center; color:#007bff; margin-bottom:20px; }
    input { width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:8px; }
    button { width:100%; padding:10px; background:#007bff; color:white; border:none; border-radius:8px; cursor:pointer; }
    button:hover { background:#0056b3; }
    .error { color:red; text-align:center; margin-bottom:10px; }
    a { display:block; text-align:center; margin-top:10px; color:#007bff; text-decoration:none; }
</style>
</head>
<body>
<div class="box">
    <h2>Register</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>
