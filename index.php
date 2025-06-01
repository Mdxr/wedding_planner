<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="#" class="logo"><span>W</span>edding <span>P</span>lanner<span>.</span></a>
            <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#422416"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
            <ul id="menu">
                <li><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#features">Features</a></li>
            </ul>
        </nav>
    </header>
    <main class="hero">
        <div class="info">
            <h1>Online <span>"Wedding"</span> Planning Assistant</h1>
            <p>Your one-stop wedding planning website, with all the tools you’ll need to create the dream celebration. No hidden fees! No sign-up required!</p>
            <a href="./project/login.php" class="btn">Start a new Wedding Project</a>
        </div>
        <div class="visual">
            <img src="media/vecteezy_wedding-cartoon-love-together-clipart-free-cute-kawaii_.jpg" alt="">
        </div>
    </main>
    <div class="banner">
        <marquee behavior="" direction="left"><h1>Our service has assisted tens of thousands of couples worldwide in planning their dream weddings!</h1></marquee>
    </div>
    <section id="features">
        <h2>Features</h2>
        <img src="./media/flower-line-broder-3.png" alt="">
        <ul>
            <div class="feature">
                <img src="./media/plan.png" alt="">
                <h2>Plan Together</h2>
                <p>Collaborate with your partner and loved ones to create the perfect wedding plan.</p>
            </div>
            <div class="feature">
                <img src="./media/tools.png" alt="">
                <h2>Online wedding tools</h2>
                <p>Access a variety of online tools to help you plan your wedding, from budget trackers to guest list managers.</p>
            </div>
            <div class="feature">
                <img src="./media/cloud.png" alt="">
                <h2>Stored Safely</h2>
                <p>Rest assured that your wedding plans and details are stored securely and privately.</p>
            </div>
        </ul>
    </section>
    <section id="about">
        <h2>About Us</h2>
        <img src="./media/flower-line-broder-3.png" alt="">
        <p>We are a team of passionate wedding planners who have come together to create a platform that makes wedding planning easier and more accessible for everyone. Our goal is to provide you with the tools and resources you need to plan your dream wedding without the stress and hassle.</p>
        <a href="./project/login.php" class="btn">Create Project Now</a>
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

    <script src="scripts/main.js"></script>
</body>
</html>