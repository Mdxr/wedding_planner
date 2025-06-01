<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../connection/connection.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
$userId;

if (isset($_GET['user']) && isset($_GET['email']) && isset($_GET['name'])) {
    $userType = $_GET['user'];
    $email = $_GET['email'];
    $name = $_GET['name'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed (user query): " . $conn->error);
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userId = $user['id'];

    if ($user) {
        $projectCheck = "SELECT * FROM projects WHERE user_id = ?";
        $stmt = $conn->prepare($projectCheck);
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();
        $projectResult = $stmt->get_result();
        $existingProject = $projectResult->fetch_assoc();
        if (!$existingProject) {
            $insertQuery = "INSERT INTO projects (user_id, user_type, venue, budget, groom, bride, groom_guardian, bride_guardian) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($insertQuery);
            if (!$stmt2) {
                die("Prepare failed (insert): " . $conn->error);
            }
            $venue = 'not set';
            $budget = 0.0;
            $bride = '';
            $groom = '';
            $groomGuardian = '';
            $brideGuardian = '';
            $userType = $_GET['user'];
            if ($userType == 'groom') {
                $groom = $name;
            } elseif ($userType == 'bride') {
                $bride = $name;
            } else {
                die("Invalid user type");
            }
            $stmt2->bind_param('issdssss', $user['id'], $userType, $venue, $budget, $groom, $bride, $groomGuardian, $brideGuardian);
            if ($stmt2->execute()) {
                $project = getProjectDetails($conn, $user['id']);
            }
        } else {
            $project = getProjectDetails($conn, $user['id']);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save-general-btn'])) {
        $bride = $_POST['bride_name'];
        $groom = $_POST['groom_name'];
        $brideGuardian = $_POST['bride_guardian_name'];
        $groomGuardian = $_POST['groom_guardian_name'];
        $weddingDate = $_POST['wedding_date'];
        $weddingTime = $_POST['wedding_time'];
        $budget = $_POST['budget'];

        $updateGeneralQuery = "UPDATE projects SET bride=?, bride_guardian=?, groom=?, groom_guardian=?, wedding_date=?, wedding_time=?, budget=? WHERE id=?";
        $stmt = $conn->prepare($updateGeneralQuery);
        $stmt->bind_param("ssssssdi", $bride, $brideGuardian, $groom, $groomGuardian, $weddingDate, $weddingTime, $budget, $project['id']);
        $stmt->execute();
        header("Location: project.php?user=" . $userType . "&email=" . $email . "&name=" . $name);
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['venue-save-btn'])) {
        $venue = $_POST['venue-name'];
        $venueQuery = "UPDATE projects SET venue=? WHERE id=?";
        $vStmt = $conn->prepare($venueQuery);
        $vStmt->bind_param("si", $venue, $project['id']);
        $vStmt->execute();
        header("Location: project.php?user=" . $userType . "&email=" . $email . "&name=" . $name);
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add-guest-btn'])) {
        $guestName = $_POST['guest-name'];
        $guestAddress = $_POST['guest-address'];
        $guestPhone = $_POST['guest-phone'];

        if(empty($guestName) || empty($guestAddress) || empty($guestPhone)){
            $guestError = "All fields are Required";
        } else {
            $insertGuestQuery = "INSERT INTO guests (name, address, phone, project_id) VALUES (?, ?, ?, ?)";
            $gStmt = $conn->prepare($insertGuestQuery);
            if (!$gStmt) {
                die("Prepare failed (insert guest): " . $conn->error);
            }
            $gStmt->bind_param("sssi", $guestName, $guestAddress, $guestPhone, $project['id']);
            if ($gStmt->execute()) {
                header("Location: project.php?user=" . $userType . "&email=" . $email . "&name=" . $name);
            } else {
                echo "Error adding guest: " . $conn->error;
            }
        }
    }
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add-menu-item-btn'])) {
        $menuItemName = $_POST['menu-item-name'];
        $menuItemDescription = $_POST['menu-item-description'];
        $menuItemPrice = $_POST['menu-item-price'];

        if(empty($menuItemName) || empty($menuItemDescription) || empty($menuItemPrice)){
            $menuErr = "All fields are required";
        } else {
            $insertMenuQuery = "INSERT INTO menu_items (name, description, price, project_id) VALUES (?, ?, ?, ?)";
            $mStmt = $conn->prepare($insertMenuQuery);
            if (!$mStmt) {
                die("Prepare failed (insert menu item): " . $conn->error);
            }
            $mStmt->bind_param("ssdi", $menuItemName, $menuItemDescription, $menuItemPrice, $project['id']);
            if ($mStmt->execute()) {
                header("Location: project.php?user=" . $userType . "&email=" . $email . "&name=" . $name);
            } else {
                echo "Error adding menu item: " . $conn->error;
            }
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete-menu-id']) && isset($_POST['delete-menu-btn'])) {
        $menuId = $_POST['delete-menu-id'];
        $deleteMenuQuery = "DELETE FROM menu_items WHERE id = ? AND project_id = ?";
        $mStmt = $conn->prepare($deleteMenuQuery);
        $mStmt->bind_param("ii", $menuId, $project['id']);
        $mStmt->execute();
        header("Location: project.php?user=" . $userType . "&email=" . $email . "&name=" . $name);
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete-guest-id']) && isset($_POST['delete-guest-btn'])) {
        $guestId = $_POST['delete-guest-id'];
        $deleteGuestQuery = "DELETE FROM guests WHERE id = ? AND project_id = ?";
        $dStmt = $conn->prepare($deleteGuestQuery);
        $dStmt->bind_param("ii", $guestId, $project['id']);
        $dStmt->execute();
        header("Location: project.php?user=" . $userType . "&email=" . $email . "&name=" . $name);
    }
}

function getProjectDetails($conn, $userId)
{
    $query = "SELECT * FROM projects WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed (project details): " . $conn->error);
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_assoc();
    return $results;
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

<body>
    <header>
        <nav>
            <a href="../index.php" class="logo"><span>W</span>edding <span>P</span>lanner<span>.</span></a>
            <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#422416"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../index.php#about">About</a></li>
                <li><a href="../index.php#features">Features</a></li>
            </ul>
            <div class="sidebar" id="menu">
                <a href="#general" class="tab-link-m active">General Info</a>
                <a href="#venue" class="tab-link-m">Venue</a>
                <a href="#guests" class="tab-link-m">Guest List</a>
                <a href="#menu" class="tab-link-m">Menu</a>
                <a href="#card" class="tab-link-m" >card</a>
                <a href="../index.php">home</a>
            </div>
        </nav>
    </header>
    <section class="project">
        <div class="sidebar">
                <a href="#general" class="tab-link active">General Info</a>
                <a href="#venue" class="tab-link">Venue</a>
                <a href="#guests" class="tab-link">Guest List</a>
                <a href="#menu" class="tab-link">Menu</a>
                <a href="#card" class="tab-link" >card</a>
            </div>
        <form class="main-content" method="POST" action="#">
            <section id="general" class="tab active">
                <div class="bride-groom-details">
                    <div class="groom">
                        <h3>Groom Details</h3>
                        <div class="inputs">
                            <input type="text" placeholder="Groom Name" value="<?php echo $project['user_type'] == 'groom' ? $user['name'] : $project['groom'] ?>" name="groom_name">
                            <input type="text" placeholder="Groom's Guardian Name" name="groom_guardian_name" value="<?php echo $project['groom_guardian'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="bride">
                        <h3>Bride Details</h3>
                        <div class="inputs">
                            <input type="text" placeholder="Bride Name" value="<?php echo $project['user_type'] == 'bride' ? $user['name'] : $project['bride'] ?>" name="bride_name">
                            <input type="text" placeholder="Bride's Guardian Name" name="bride_guardian_name" value="<?php echo $project['bride_guardian'] ?? '' ?>">
                        </div>
                    </div>
                </div>
                <div class="inputs">
                    <input onfocus="(this.type='date')" onblur="if(!this.value) this.type='text'" placeholder="Wedding Date" value="<?php echo $project['wedding_date'] ?? '' ?>" name="wedding_date">
                    <input onfocus="(this.type='time')" onblur="if(!this.value) this.type='text'" type="text" placeholder="Wedding Time" value="<?php echo $project['wedding_time'] ?? '' ?>" name="wedding_time">
                    <input type="number" placeholder="Budget" value="<?php echo $project['budget'] ?>" name="budget">
                </div>
                <button name="save-general-btn" type="submit" class="btn" style="margin: 1rem 0;">save details</button>
            </section>
            <section id="venue" class="tab">
                <input type="hidden" value="<?php echo $project['venue'] ?>" id="venue-name" name="venue-name">
                <h1>Outdoors</h1>
                <div class="venues-container">
                    <a class="venue">
                        <img src="../media/outdoor-1.png" alt="">
                        <h3>Water's Edge</h3>
                        <p>Situated in Bani Gala, it provides serene lakeside views, ideal for romantic ceremonies.</p>
                    </a>

                    <a class="venue">
                        <img src="../media/outdoor-2.png" alt="">
                        <h3>Rawal Lake View Park</h3>
                        <p> A public park offering scenic views of Rawal Lake and the Margalla Hills, suitable for natural and rustic wedding settings.</p>
                    </a>
                    <a class="venue">
                        <img src="../media/outdoor-3.png" alt="">
                        <h3>1969 & Time Goes On</h3>
                        <p> Located in Shakarparian National Park, this venue offers a lush, nature-filled setting.</p>
                    </a>
                    <a class="venue">
                        <img src="../media/outdoor-4.png" alt="">
                        <h3>The Orchard by RMK</h3>
                        <p>Located on Japan Road, this farmhouse offers a rustic charm for intimate gatherings.</p>
                    </a>
                </div>
                <h1>Indoors</h1>
                <div class="venues-container">
                    <a class="venue">
                        <img src="../media/indoor-1.png" alt="">
                        <h3>Islamabad Club</h3>
                        <p>A prestigious venue known for its grandeur, featuring well-maintained lawns and banquet halls for both intimate and grand celebrations.</p>
                    </a>
                    <a class="venue">
                        <img src="../media/indoor-2.png" alt="">
                        <h3>Ramada by Wyndham</h3>
                        <p>Offers contemporary banquet halls and outdoor spaces with state-of-the-art facilities and expert event planning services.</p>
                    </a>
                    <a class="venue">
                        <img src="../media/indoor-3.png" alt="">
                        <h3>Majesty Marquee</h3>
                        <p>Located on Expressway Road, this venue provides elegant indoor spaces suitable for large gatherings. </p>
                    </a>
                    <a class="venue">
                        <img src="../media/indoor-4.png" alt="">
                        <h3>Saffron Marquee</h3>
                        <p>Situated opposite COMSATS University on Park Road, it offers spacious halls with customizable décor options.</p>
                    </a>
                </div>
                <button type="submit" name="venue-save-btn" class="btn">save venue</button>
            </section>
            <section id="guests" class="tab">
                <div class="guest-form inputs">
                    <h1>Guests</h1>
                    <input type="text" placeholder="Guest Name" name="guest-name">
                    <input type="text" placeholder="Guest Address" name="guest-address">
                    <input type="number" placeholder="Guest Phone" name="guest-phone">
                    <button type="submit" class="btn" id="add-guest-btn" name="add-guest-btn">add guest</button>
                    <p class="error"><?php echo $guestError ?? '' ?></p>
                </div>
                <table class="guest-list">
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $guestQuery = "SELECT * FROM guests WHERE project_id = ?";
                    $guestStmt = $conn->prepare($guestQuery);
                    if (!$guestStmt) {
                        die("Prepare failed (guest query): " . $conn->error);
                    }
                    $guestStmt->bind_param('i', $project['id']);
                    $guestStmt->execute();
                    $guestResult = $guestStmt->get_result();
                    while ($guest = $guestResult->fetch_assoc()) {
                        echo "<tr>
                    <td>{$guest['name']}</td>
                    <td>{$guest['address']}</td>
                    <td>{$guest['phone']}</td>
                    <td>
                        <form method='POST' style='display:inline-block;'>
                            <input type='hidden' name='delete-guest-id' value='{$guest['id']}'>
                            <button type='submit' class='delete-btn' onclick=\"return confirm('Delete this guest?')\">Delete</button>
                        </form>
                    </td>
                  </tr>";
                    }
                    ?>
                </table>
            </section>
            <section id="menu" class="tab">
                <div class="menu-form inputs">
                    <h1>Menu Items</h1>
                    <input type="text" placeholder="Item Name" name="menu-item-name">
                    <input type="text" placeholder="Description" name="menu-item-description">
                    <input type="number" step="0.01" placeholder="Price" name="menu-item-price">
                    <button type="submit" class="btn" id="add-menu-item-btn" name="add-menu-item-btn">Add Menu Item</button>
                    <p class="error"><?php echo $menuErr ?? '' ?></p>
                </div>
                <table class="menu-list">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    $menuQuery = "SELECT * FROM menu_items WHERE project_id = ?";
                    $menuStmt = $conn->prepare($menuQuery);
                    if (!$menuStmt) {
                        die("Prepare failed (menu query): " . $conn->error);
                    }
                    $menuStmt->bind_param('i', $project['id']);
                    $menuStmt->execute();
                    $menuResult = $menuStmt->get_result();
                    while ($item = $menuResult->fetch_assoc()) {
                        echo "<tr>
                <td>{$item['name']}</td>
                <td>{$item['description']}</td>
                <td>{$item['price']}</td>
                <td>
                    <form method='POST' style='display:inline-block;'>
                        <input type='hidden' name='delete-menu-id' value='{$item['id']}'>
                        <button type='submit' class='delete-btn' onclick=\"return confirm('Delete this item?')\" name='delete-menu-btn'>Delete</button>
                    </form>
                </td>
              </tr>";
                    }
                    ?>
                </table>
            </section>
            <section id="card" class="tab">
                <h1>Invitation card</h1>
                <div class="card">
                    <img src="../media/flower-line-broder-3.png" alt="">
                    <h2><?php echo $project['groom'] . "<span> Weds </span>" . $project['bride'] ?></h2>
                    <p><strong>Date:</strong> <?php echo $project['wedding_date'] ?></p>
                    <p><strong>Time:</strong> <?php echo $project['wedding_time'] ?></p>
                    <p><strong>Venue:</strong> <?php echo $project['venue'] ?></p>
                    <img src="../media/flower-line-broder-3.png" alt="">
                </div>
            </section>
        </form>
        
        <script src="../scripts/main.js"></script>
</body>

</html>