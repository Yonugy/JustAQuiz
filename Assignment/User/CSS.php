<?php
include('../../main.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustAQuiz</title>
    <link rel="stylesheet" href="CSS.css">
</head>
<body>
    <header>
        <div class="logo">
            <div id="h1">JUST</div><div id="h2">A</div><div id="h3">QUIZ</div>
        </div>
        <div class="quiz-id">
            <span>JOIN QUIZ? ENTER QUIZID: </span>
            <input id="joinQuiz" type="text" placeholder="Enter ID">
        </div>
        <nav>
        <?php //login button appear or not
                if (!isset($_SESSION['user_id'])) { //already login
                    echo '<div class="btn"><a href="Login.php">Login</a></div>';
                }else{ //not yet log in
                    echo '<div class="emptyBtn"></div>';
                }
            ?>
        </nav>
    </header>

    <nav class="navbar">
        <a href="Home.php">HOME</a>
        <a href="Option.php">QUIZZES</a>
        <a href="DashBoard.php">DASHBOARD</a>
        <a href="MyProfile.php">MY PROFILE</a>
    </nav>

    <main>   
        <section class="category">
            <h2>CSS</h2>
            <div class="cards">
            <?php
                    $sql = "SELECT * FROM Quiz WHERE subject = 'CSS'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $count=1;
                        while ($row = $result->fetch_assoc()) {
                            # echo html code here
                            echo '<div class="card">';
                            echo '<h3>' . $row['title'] . '</h3>';
                            echo '<p>' . $row['description'] . '</p>';
                            // echo '<button class="button-73" role="button" onclick="window.location.href='StartQuiz.php'">Play</button>';
                            echo '<button class="button-73" role="button" onclick="window.location.href=\'StartQuiz.php?id='.$row['quiz_id'] .'\'">Play</button>';
                            echo '</div>';
                        }
                    } else {
                        echo "No quiz available at the moment.";
                    }
                ?>
            </div>
            
        </section>
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
