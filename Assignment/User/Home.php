<?php
include('../../main.php');
//user can access to this page no matter logged in or not



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JustAQuiz</title>
    <link rel="stylesheet" href="Home.css">
    <style>
        #picture1 {
            justify-content: center;
            align-items: center;
            background-image: url(../images/RemoveJustAQuiz.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: 100% 100% cover;
            width: 100%;
            height: 500px;  
            border: 3px solid rgb(255, 255, 255);
            border-radius: 20px;
            box-shadow: 0 0 30px white;
        }

        #rocket {
            position: absolute;
            width: 300px;
            height: auto;
            animation: flyRocket 10s ease-in-out infinite; 
        }

        @keyframes flyRocket {
        
        0% {
            top: 550px; 
            left: 670px;
            transform: rotate(-30deg);
        }

        10%{
            top: 450px; 
            left: 670px;
            transform: rotate(-30deg);
        }
        20% {
            top: -300px;
            left: 1000px;
            transform: rotate(45deg);
        }
        30% {
            top: -100px;
            left: 1300px;
            transform: rotate(145deg);         
        } 
        40% {
            top: 700px;
            left: 1300px; 
            transform: rotate(180deg);
            
        }
        50% {
            top: 1000px;
            left: 900px; 
            transform: rotate(245deg);     
        }
        
        60% {
            top: 700px;
            left: 200px; 
            transform: rotate(245deg);
        }
        70% {
            top: 400px;
            left: -200px;
            transform: rotate(275deg); 
        }
        80% {
            top: 100px;
            left: 100px; 
            transform: rotate(360deg); 
        }
        90% {
            top: 300px;
            left: 400px;
            transform: rotate(200deg); 
        }       
        100% {
            top: 550px; 
            left: 670px;
            transform: rotate(-30deg);
        }
    }
    </style>
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
        <div id="picture1">
            <img id="rocket" src="../images/Rocket.png" alt="rocket">
        </div>      
        <div id=MainB><button class="Mainbutton" href="#cards" onclick="window.location.href='Option.php'">Let's Start</button></div>
        <section class="category">
            <h2>HTML</h2>
            <div class="cards">
                <?php
                    $sql = "SELECT * FROM Quiz WHERE subject = 'HTML'";
                    $result = $conn->query($sql);
                    $quiz_amount=$result->num_rows;

                    if ($quiz_amount > 0) {
                        $count=0;
                        while ($row = $result->fetch_assoc()) {
                            # echo html code here
                            echo '<div class="card">';
                            echo '<h3>' . $row['title'] . '</h3>';
                            echo '<p>' . $row['description'] . '</p>';
                            echo '<button class="button-73" role="button" onclick="window.location.href=\'StartQuiz.php?id='.$row['quiz_id'] .'\'">Play</button>';
                            echo '</div>';
                            $count++;
                            if ($count>=4 && $quiz_amount>5){
                                break;
                            }
                        }
                    } else {
                        echo "No quiz available at the moment.";
                    }
                    if ($quiz_amount>5){
                        echo '<button id="See-All1" onclick="window.location.href=\'HTML.php\'">See All</button>';
                    }
                ?>
            </div>
           
        </section>

        <section class="category">
            <h2>CSS</h2>
            <div class="cards">
                <?php
                    $sql = "SELECT * FROM Quiz WHERE subject = 'CSS'";
                    $result = $conn->query($sql);
                    $quiz_amount=$result->num_rows;

                    if ($quiz_amount > 0) {
                        $count=0;
                        while ($row = $result->fetch_assoc()) {
                            # echo html code here
                            echo '<div class="card">';
                            echo '<h3>' . $row['title'] . '</h3>';
                            echo '<p>' . $row['description'] . '</p>';
                            // echo '<button class="button-73" role="button" onclick="window.location.href='StartQuiz.php'">Play</button>';
                            echo '<button class="button-73" role="button" onclick="window.location.href=\'StartQuiz.php?id='.$row['quiz_id'] .'\'">Play</button>';
                            echo '</div>';
                            $count++;
                            if ($count>=4 && $quiz_amount>5){
                                break;
                            }
                        }
                    } else {
                        echo "No quiz available at the moment.";
                    }
                    if ($quiz_amount>5){
                        echo '<button id="See-All1" onclick="window.location.href=\'CSS.php\'">See All</button>';
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
