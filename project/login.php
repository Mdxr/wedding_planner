<?php
include '../connection/connection.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $emailErr = $passwordErr = '';

    if (empty($email)) {
        $emailErr = 'Email is required';
    }
    if (empty($password)) {
        $passwordErr = 'Password is required';
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            header("Location: create.php?email=" . $email . "&name=" . $result['name']);
            exit();
        } else {
            $emailErr = 'Invalid email or password';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body class="auth-body">
    <form action="#" method="POST" class="auth-form">
        <h1>Login</h1>
        <img src="../media/tools.png" alt="">
        <img class="flower-img" src="../media/flower-line-broder-3.png" alt="">
        <input type="email" id="email" name="email" value="<?php echo $email ?>">
        <p class="error"><?php echo $emailErr ?></p>
        <input type="password" id="password" name="password" value="<?php echo $password ?>">
        <p class="error"><?php echo $passwordErr ?></p>

        <button type="submit" class="btn">Login</button>
        <p>Don't have an account? <a href="register.php">register</a></p>
    </form>
</body>
</html>