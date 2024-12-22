<?php
include('../../main.php');
include('../session.php');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustAQuiz</title>
    <link rel="stylesheet" href="DashBoard.css">
</head>
<body>
    <header>
        <div class="logo">
            <div id="h1">JUST</div><div id="h2">A</div><div id="h3">QUIZ</div>
        </div>
        <button id="view-report" onclick="window.location.href='Report.php'">View Report</button>
    </header>

    <nav class="navbar">
        <a href="Home.php">HOME</a>
        <a href="Option.php">QUIZZES</a>
        <a href="DashBoard.php">DASHBOARD</a>
        <a href="MyProfile.php">MY PROFILE</a>
    </nav>
    <section>
            <div class='air air1'></div>
            <div class='air air2'></div>
            <div class='air air3'></div>
            <div class='air air4'></div>
      </section>
    <main> 
        <div class="quiz-id">
            <span>JOIN QUIZ? ENTER QUIZID: </span>
            <input id="joinQuiz" type="text" placeholder="Enter ID">
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Subject</th>
                    <th scope="col">Title-Description</th>
                    <th scope="col">Quiz Summary</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Time Taken</th>
                    <th scope="col">Completion Date</th>
                    <th scope="col">Feedback</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $student_id = $_SESSION['user_id'];
                    $sql = "SELECT q.quiz_id, q.subject, q.title, q.description, r.datetime, r.feedback, a.attempt_id FROM Attempt a INNER JOIN Quiz q ON a.quiz_id = q.quiz_id INNER JOIN Result r ON r.attempt_id = a.attempt_id WHERE a.student_id = ? AND a.stat = 'completed' ORDER BY r.datetime DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $student_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                
                    if ($result->num_rows > 0) { //not fixed
                      while ($row = $result->fetch_assoc()) {
                        $comma_separated = implode(" ", $row);
                        echo "<script>console.log('$comma_separated');</script>";
                        $subject = htmlspecialchars($row['subject']);
                        $title_description = htmlspecialchars($row['title']) . " - " . htmlspecialchars($row['description']);
                        $score = calculate_score($row['attempt_id'], $conn);
                        echo "<script>console.log('$score');</script>";
                        $total_questions = total_question($row['quiz_id'], $conn);
                        echo "<script>console.log('$total_questions');</script>";
                        if ($total_questions>0){
                          $quiz_summary = ($score / $total_questions) * 100 . "%";
                          $grade = calculate_grade($score, $total_questions);
                        }else{
                          $quiz_summary = "0%";
                          $grade = "F";
                        }
                        $used_time = number_format(calculate_used_time($row['attempt_id'], $conn), 2)*60 . "s";
                        $datetime = htmlspecialchars($row['datetime']);
                        $date = explode(" ", $datetime)[0];
                        $feedback = htmlspecialchars($row['feedback']);
                        echo "<tr>";
                        echo "<th scope='col'>$subject</th>";
                        echo "<th scope='col'>$title_description</th>";
                        echo "<th scope='col'>$quiz_summary</th>";
                        echo "<th scope='col'>$grade</th>";
                        echo "<th scope='col'>$used_time</th>";
                        echo "<th scope='col'>$date</th>";
                        echo "<th scope='col'>$feedback</th>";
                      }
                    }
                  ?>
                  </tr>
                </tbody>
              </table>
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
        textbox = document.getElementById("joinQuiz");
        textbox.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                quizid=textbox.value;
                window.location.href="StartQuiz.php?id="+quizid;
            }
        });
    </script>
</body>
</html>
