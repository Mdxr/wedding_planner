<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
    <header>
        <nav>
            <h1 class="logo"><span>W</span>edding <span>P</span>lanner<span>.</span></h1>
            <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#422416"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>

            <ul id="menu">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../index.php#about">About</a></li>
                <li><a href="../index.php#features">Features</a></li>
            </ul>
        </nav>
    </header>
    <section id="user-selection">
        <h1><?php echo $_GET['name']?>, introduce yourself as</h1>
        <div class="options">
            <a href="project.php<?php echo "?user=groom".$_GET['user']."&email=".$_GET['email']."&name=".$_GET['name'] ?>" class="card" name="groom">
                <img src="../media/groom.png" alt="">
                <h2>Groom</h2>
            </a>
            <a href="project.php<?php echo "?user=bride".$_GET['user']."&email=".$_GET['email']."&name=".$_GET['name'] ?>" class="card" name="bride">
                <img src="../media/bride.png" alt="">
                <h2>Bride</h2>
            </a>
        </div>
    </section>
    <footer>
        <div>
            <h1 class="logo"><span>W</span>edding <span>P</span>lanner<span>.</span></h1>
            <p>&copy; 2025 Wedding Planner. All rights reserved.</p>
        </div>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#about">About Us</a></li>
        </ul>
    </footer>
    <script src="../scripts/main.js"></script>
</body>
</html>