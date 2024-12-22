<?php
include("../../main.php");
include('../session.php');

// Check if it's an AJAX request for badge deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deleteBadge' && isset($_POST['badge_id'])) {
    $badge_id = $_POST['badge_id'];
    
    // Include the function that handles the badge deletion
    if (delete_badge($badge_id, $conn)) {
        echo "Badge deleted successfully.";
    } else {
        echo "Failed to delete the badge.";
    }
}

function creator_display_badges($conn) {
    $creator_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM Badges WHERE creator_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $creator_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='box'>";
            echo "<img src='data:" . htmlspecialchars($row['image_type']) . ";base64," . base64_encode($row['badge_image']) . "' alt='Badge Image' />";

            echo "<div class='box-word'>";
            echo "<p>Name: " . htmlspecialchars($row['achievement_name']) . "</p>";
            echo "<p>Category: " . htmlspecialchars($row['category']) . "</p>";
            echo "<p>Criteria: " . htmlspecialchars($row['criteria']) . "</p>";
            echo "<p>Date created: " . htmlspecialchars($row['date']) . "</p>";
            echo "</div>";

            echo "<button class='del-button' onclick='deleteBadge(" . htmlspecialchars($row['badge_id']) . ")'>Delete</button>";
            echo "</div>";
        }
    } else {
        echo "<p>No badges found for this creator.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="Badges.css">
</head>
<body>
    <header>
    <div class="logo">
            <div id="h1">JUST</div><div id="h2">A</div><div id="h3">QUIZ</div>
        </div>
    </header>

    <nav class="navbar">
        <a href="AdminHome.php">HOME</a>
        <a href="AdminCreateQuiz.php">CREATE QUIZ</a>
        <a href="UserManagement.php">MANAGEMENT</a>
        <a href="Badges.php">BADGES</a>
        <a href="../User/Login.php">LOGOUT</a>
    </nav>

    <main>
        <div class="container">
            <div class="left-container">
                <h2>CREATE BADGE</h2>
                <form method="post" enctype="multipart/form-data">
                    <input type="text" placeholder="Name" id="badge-name" name ="badgename" class="form-input" required>
                    <select type="text" placeholder="Category" id="badge-category" name="badgecategory" class="form-input" required>
                        <option value="" disabled selected>Category</option>
                        <option value = "HTML">HTML</option>
                        <option value = "CSS">CSS</option>
                    </select>
                    <input type="text" placeholder="Criteria" id="badge-grade" name="badgecriteria" class="form-input" required>
                    <label for="images" class="drop-container" id="dropcontainer" class="form-input">
                    <span class="drop-title">Drop files here</span>
                    or
                    <input type="file" id="images" accept="image/*" name="badgeimage" required>
                    </label>
                    <button id="create-badge" name="createBtn">CREATE</button>
                </form>
            </div>

            <?php
            if (isset($_POST['createBtn'])) {

                if (!isset($_FILES['badgeimage']) || $_FILES['badgeimage']['error'] !== UPLOAD_ERR_OK) {
                    echo '<script>alert("File upload failed. Please try again.");</script>';
                    exit;
                }


                $status = create_badge($_SESSION['user_id'], $_POST['badgename'], $_POST['badgecategory'], $_POST['badgecriteria'], $_FILES['badgeimage'], $conn);

                if ($status) {
                    echo "<script>alert('Badge created successfully!');</script>";
                } else {
                    echo "<script>alert('Badge creation failed. Please try again.'); </script>";
                }
        }
    ?>
            <div class="right-container">
                <h2>BADGES</h2>
                <div class="badge-container">
                    <?php creator_display_badges($conn); ?>
                </div>
            </div>
        </div>
    </main>
    <ul class="bg-bubbles">
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
    <script>
        function deleteBadge(badgeId) {
            // Create an AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);  // Use the current PHP file (empty string for the current URL)
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Send the badge ID to the server along with the action
            xhr.send("badge_id=" + badgeId + "&action=deleteBadge");

            // When the request completes
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // If successful, remove the badge from the DOM
                    var badgeDiv = document.getElementById("badge_" + badgeId);
                    if (badgeDiv) {
                        badgeDiv.remove(); // Remove the badge element
                    }
                    alert("Badge deleted successfully.");
                    location.reload();
                    window.location.href = "Badges.php";
                } else {
                    alert("Error deleting badge.");
                }
            };
        }
    </script>
</body>
</html>