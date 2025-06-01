<?php
    include_once '../connection/connection.php';
    session_start();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cPassword = $_POST['cPassword'];

        $nameErr = $emailErr = $passwordErr = $cPasswordErr = '';
        
        if(empty($name)){
            $nameErr = 'Name is required';
        }
        if(empty($email)){
            $emailErr = 'Email is required';
        }
        if(empty($password)){
            $passwordErr = 'Password is required';
        } elseif(strlen($password) < 6){
            $passwordErr = 'Password must be at least 6 characters';
        }
        if($password !== $cPassword){
            $cPasswordErr = 'Confirm Pasword';
        }
        if(empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($cPasswordErr)){
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            if (!empty($result)) {
                $emailErr = 'Email already exists';
            } else {
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $password);
                if ($stmt->execute()) {
                    header("Location: create.php?email=" . $email . "&name=" . $name);
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            }
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
        <h1>Register</h1>
        <img src="../media/tools.png" alt="">
        <img class="flower-img" src="../media/flower-line-broder-3.png" alt="">
        <input type="text" id="name" name="name" placeholder="Name" value="<?php echo $name ?>">
        <p class="error"><?php echo $nameErr ?></p>

        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $email ?>">
        <p class="error"><?php echo $emailErr ?></p>

        <input type="password" id="password" name="password" placeholder="Password">
        <p class="error"><?php echo $passwordErr ?></p>

        <input type="password" id="c-password" name="cPassword" placeholder="Confirm Password">
        <p class="error"><?php echo $cPasswordErr ?></p>

        <button type="submit" class="btn">Register</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
</body>
</html>