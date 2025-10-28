<?php
require 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            header("Location: chat.html");
            exit;
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "⚠️ User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
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
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (isset($_GET['registered'])) echo "<p style='color:green;text-align:center;'>✅ Registration successful! Please login.</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="register.php">Create an account</a>
</div>
</body>
</html>
