<?php
include('../../main.php');
include('../session.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $quiz = mysqli_query($conn, "SELECT * FROM quiz WHERE quiz_id=$id");
    if (mysqli_num_rows($quiz) < 1) {
        echo "<script>alert('Invalid Quiz ID.');window.location.href='Home.php';</script>";
    }
    $row = mysqli_fetch_array($quiz);
    $subject = $row['subject'];
    $title = $row['title'];
    $description = $row['description'];
    $time = $row['time_limit'];
    $amount=total_question($id, $conn);
} else {
    echo "<script>alert('Please choose quiz to start.');window.location.href='Home.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustAQuiz</title>
    <link rel="stylesheet" href="StartQuiz.css">
</head>
<body>
    <header>
        <div class="logo">
            <div id="h1">JUST</div><div id="h2">A</div><div id="h3">QUIZ</div>
        </div>
        <div >
            <h1 id="Title"><?php echo $subject; ?> TIME</h1>
        </div>
        <div id="info"></div>
    </header>

    <nav class="navbar">
        <a href="Home.php">HOME</a>
        <a href="Option.php">QUIZZES</a>
        <a href="DashBoard.php">DASHBOARD</a>
        <a href="MyProfile.php">MY PROFILE</a>
    </nav>
    <main>
        <div id="main"> 
            <div id="container">
                <h1 class="Title"><?php echo $title; ?></h1>
                <h1 class="Title"><?php echo $description; ?></h1>
                <h1 class="Title2"><?php echo $amount; ?> Question</h1>
                <h1 class="Title2"><?php echo $time; ?> minutes</h1>
                <button class="Start" onclick="window.location.href='Quiz.php?id=<?php echo $id; ?>&q=1'">Start</button>
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
</body>
</html>
