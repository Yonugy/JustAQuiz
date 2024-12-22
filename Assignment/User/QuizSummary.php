<?php
include('../../main.php');
include('../session.php');
//retrieve quiz id of attempt
$attemptid=$_SESSION['attempt_id'];
$attempt = mysqli_query($conn, "SELECT * FROM attempt WHERE attempt_id=$attemptid");
$row = mysqli_fetch_array($attempt);
$quizid = $row['quiz_id'];

//retrieve info from quiz
$quiz = mysqli_query($conn, "SELECT * FROM quiz WHERE quiz_id=$quizid");
$row = mysqli_fetch_array($quiz);
$subject=$row['subject'];
$topic=$row['title'];
$amount=total_question($quizid, $conn);
$score=calculate_score($attemptid,$conn)/$amount*100;

//find answered question (choice id not equals -1)
$answered_question = mysqli_query($conn, "SELECT COUNT(*) as answered FROM student_answer WHERE attempt_id=$attemptid AND CHOICE_ID!=-1");
$answered = mysqli_fetch_array($answered_question)['answered'];

//find time used to complete quiz and format into mm:ss
$timespent=calculate_used_time($attemptid, $conn); //time spent in minute need convert to minute and second
$seconds=sprintf("%02d", ($timespent - floor($timespent))*60);
$minutes=(int)$timespent;
$time="$minutes:$seconds";

//find grade
$grade=calculate_grade(calculate_score($attemptid,$conn),$amount);

//access result of the attempt
$result = mysqli_query($conn, "SELECT * FROM result WHERE attempt_id=$attemptid");
$row = mysqli_fetch_array($result);

//today's date
$date=date("F j, Y");

//get feedback
if ($row['feedback']===null){
    $feedback="-";
}else{
    $feedback=$row['feedback'];
}

//pie chart
$greenAngle = ($score / 100) * 360; // Example: Starting angle for the green section
$redAngle = 360; // Example: Ending angle for the pink section
echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        let greenAngle = $greenAngle; // PHP value inserted into JS variable
        document.querySelector('.pie-chart').style.backgroundImage = 
            `conic-gradient(
                #00ff00 0deg {$greenAngle}deg, 
                #f39fab {$greenAngle}deg 360deg
            )`;
    });
</script>";
unset($_SESSION['attempt_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustAQuiz</title>
    <link rel="stylesheet" href="QuizSummary.css">
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
            <div class="left">
                <div class="title">Quiz Result:</div>
                <div class="info">
                    <div class="info-item">
                        <div class="label">Subject</div>
                        <div class="value"><?php echo $subject; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="label">Completed in</div>
                        <div class="value"><?php echo $time; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="label">Topic</div>
                        <div class="value"><?php echo $topic; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="label">Date</div>
                        <div class="value"><?php echo $date; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="label">Questions answered</div>
                        <div class="value"><?php echo $answered; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="label">Grade</div>
                        <div class="value"><?php echo $grade; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="label">Total score</div>
                        <div class="value"><?php echo $score; ?>%</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Feedback</div>
                        <div class="value"><?php echo $feedback; ?></div>
                    </div>
                </div>
            </div>
            <div class="chart">
                <div class="chart-title">
                    <div class="incorrect-label"> incorrect</div>
                    <div class="correct-label"> correct</div>
                </div>
                <div id="pie-chart" class="pie-chart"></div>
            </div>
        </div>
        <div class="loop-wrapper">
    <div class="mountain"></div>
    <div class="hill"></div>
    <div class="tree"></div>
    <div class="tree"></div>
    <div class="tree"></div>
    <div class="rock"></div>
    <div class="truck"></div>
    <div class="wheels"></div>
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
