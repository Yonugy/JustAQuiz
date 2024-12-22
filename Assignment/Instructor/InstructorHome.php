<?php
include("../../main.php");
include('../session.php');
$id = user_profile($conn, "user_id");
$name = user_profile($conn, "name");
$email = user_profile($conn, "email");
echo "<script>console.log('$name');</script>";
$total_quiz = calculate_total_quiz_created($conn);
$total_attempt = total_quiz_attempt($conn);

// Fetch current user data to prefill the form
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$default_image = "https://images.unsplash.com/photo-1510227272981-87123e259b17?ixlib=rb-0.3.5&q=80&fm=jpg&crop=faces&fit=crop&h=200&w=200&s=3759e09a5b9fbe53088b23c615b6312e"; // Default image URL
$profile_image_src = $default_image;

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $name = htmlspecialchars($user['name']);
    $email = htmlspecialchars($user['email']);
    // If profile image exists in the database, decode and set it
    if (!empty($user['profile_image']) && !empty($user['image_type'])) {
        $profile_image_src = "data:" . htmlspecialchars($user['image_type']) . ";base64," . base64_encode($user['profile_image']);
    }
} else {
    echo "<script>alert('User not found.'); window.location.href = '../User/Login.php';</script>";
    exit();
}

// Update user data if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_name = htmlspecialchars(trim($_POST['name']));
    $new_email = htmlspecialchars(trim($_POST['email']));
    $new_image = $_FILES['profile_image'];

    // Check if a new image is uploaded
    if (!empty($new_image['tmp_name'])) {
        $profile_image = file_get_contents($new_image['tmp_name']);
        $image_type = htmlspecialchars($new_image['type']);
    } else {
        $profile_image = $user['profile_image'];
        $image_type = $user['image_type'];
    }


    // Update the user information in the database
    $update_sql = "UPDATE users SET name = ?, email = ? ,profile_image = ?, image_type = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $new_name, $new_email, $profile_image, $image_type, $id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully.'); window.location.href = 'InstructorHome.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to update profile. Please try again.');</script>";
    }
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="InstructorHome.css">
</head>
<body>
    <header>
    <div class="logo">
            <div id="h1">JUST</div><div id="h2">A</div><div id="h3">QUIZ</div>
        </div>
        <div id="button" onclick="window.location.href='InstructorCreateQuiz.php'"><button class="create-quiz">CREATE QUIZ</button></div>
    </header>

    <nav class="navbar">
        <a href="InstructorHome.php">HOME</a>
        <a href="InstructorCreateQuiz.php">CREATE QUIZ</a>
        <a href="Overview.php">OVERVIEW</a>
        <a href="../User/Login.php">LOGOUT</a>
    </nav>

    <main>
    <div class="container">
        <div class="left-container">
            <div class="box">
                <p class="update">Last updated: <?php echo getTodayDate();?></p>
                <p class="number"><?php echo $total_quiz; ?></p>
                <p class="Total">Total Quizzes Created</p>
                <a class="link" href="Overview.php">VIEW</a>
            </div>
            <div class="box">
                <p class="update">Last updated: <?php echo getTodayDate();?></p>
                <p class="number"><?php echo $total_attempt; ?></p>
                <p class="Total">Total Attempts</p>
                <a class="link" href="Overview.php?tab=2">VIEW</a>
            </div>
        </div>
        <div class="right-container">
            <div class="profile-box">
            <div class="profilepic">
                <img class="profilepic__image" src= <?php echo $profile_image_src; ?> width="150" height="150" alt="Profibild" />
                <div class="profilepic__content">
                    <span class="profilepic__icon"><i class="fas fa-camera"></i></span>
                    <span class="profilepic__text">Edit Profile</span>
                </div>
            </div>
                <div id="down">
                    Instructor ID: <span id="info1"><?php echo $id; ?></span> 
                    <br> Name : <span id="info2"><?php echo $name; ?></span> 
                    <br> Email : <span id="info3"><?php echo $email; ?></span>
                    <!--<br> Password : <span id="info4">********</span><br>-->
                    <button class="del-button">Delete Account</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    </main>
    <div class="popup-confirm"> 
        <div class="popup-content">
            <img id="close-button" src="../images/close.png" alt="close-button">
            <h1>Are You Sure You Want to Delete this Account?</h1>
            <form method="POST">
                <button type="submit" name="confirm_delete" class="pop-up-submit">Yes</button>
            </form>
            <button class="pop-up-submit" id="pop-up-No">No</button>
        </div>
    </div>
    <div class="popup"> 
        <div class="popup-content2">
            <form method="POST" enctype="multipart/form-data">
                <img id="close-button2" src="../images/close.png" alt="close-button">
                <h2>Profile</h2>
                <hr>
                <div class="input-info">
                    <label for="basic-name">Name</label>
                    <input type="text" id="basic-name" placeholder="Name" name="name" value="<?php echo $name; ?>" required />
                </div>
                <div class="input-info">
                    <label for="additional-info">Email</label>
                    <input type="email" id="additional-info" placeholder="Email" name="email" value="<?php echo $email; ?>" required />
                </div>
                <div class="images-container">
                    <label for="images" class="drop-container" id="dropcontainer" class="form-input">
                        <span class="drop-title">Drop files here</span>
                        or
                        <input type="file" id="images" name="profile_image" accept="image/*">
                    </label>
                </div>
                <div class="button-container">
                    <button type="submit" class="update-button" name="update_profile">Update</button>
                </div>
            </form>
        </div>
    </div>
    <script src="InstructorHome.js"></script>
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
    <?php function delete_user($id, $conn) {
    $sql = "DELETE FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the session
    $id = user_profile($conn, "user_id"); // Replace this with your session management logic
    
    if ($id && delete_user($id, $conn)) {
        session_destroy(); // Destroy session to log out user
        echo json_encode(['success' => true]);
        echo '<script>alert("Success");
                    window.location.href = "../User/Login.php";
                    </script>';
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not delete account.']);
        exit();
    }
} 


?>
</body>
</html>