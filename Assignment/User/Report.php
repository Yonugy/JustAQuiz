<?php
include('../../main.php');
include('../session.php');
$id = user_profile($conn, "user_id");
$name = user_profile($conn, "name");
$report_data=overall_report($conn);
$score=$report_data[0];
$grade=$report_data[1];
$total_quiz=$report_data[2];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustAQuiz</title>
    <link rel="stylesheet" href="Report.css">
</head>
<body>
    <header>
        <div class="logo">
            <div id="h1">JUST</div><div id="h2">A</div><div id="h3">QUIZ</div>
        </div>
    </header>

    <nav class="navbar">
        <a href="Home.php">HOME</a>
        <a href="Option.php">QUIZZES</a>
        <a href="DashBoard.php">DASHBOARD</a>
        <a href="MyProfile.php">MY PROFILE</a>
    </nav>
    <main>
        <div id="main">
            <div class="header"><?php echo "$id $name"; ?></div>
            <div class="container">
                <div class="content">
                    <div class="report">
                        <h2>Overall Report</h2>
                        <div class="info">
                            <div class="info-item">
                                <div class="label">Average Score:</div>
                                <div class="value"><?php echo $score; ?></div>
                            </div>
                            <div class="info-item">
                                <div class="label">Total Quizzes Completed:</div>
                                <div class="value"><?php echo $total_quiz; ?></div>
                            </div>
                            <div class="info-item">
                                <div class="label">Average Grade:</div>
                                <div class="value"><?php echo $grade; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="right">
                        <h2>Badges</h2>
                        <div class="badges">
                            <?php
                                student_obtained_badges($id,$conn)
                            ?>
                            <!-- <div class="box">
                                <img src="../images/CSS-Badge.png" alt="CSS Knight Badge">
                                <p>CSS Knight</p>
                            </div>
                            <div class="box">
                                <img src="../images/HTML-Badge.png" alt="CSS Knight Badge">
                                <p>CSS Knight</p>
                            </div>
                            <div class="box">
                                <img src="../images/HTML-Badge.png" alt="CSS Knight Badge">
                                <p>CSS Knight</p>
                            </div>
                            <div class="box">
                                <img src="../images/HTML-Badge.png" alt="CSS Knight Badge">
                                <p>CSS Knight</p>
                            </div> -->
                        </div>
                    </div>
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
</body>
</html>
