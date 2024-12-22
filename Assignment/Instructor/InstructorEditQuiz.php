<?php
include("../../main.php");
include('../session.php');
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $quiz = mysqli_query($conn, "SELECT * FROM quiz WHERE quiz_id=$id");
    if (mysqli_num_rows($quiz) < 1) {
        echo "<script>alert('Invalid Quiz ID.');window.location.href='Overview.php';</script>";
    }
    $row = mysqli_fetch_array($quiz);
    $subject = $row['subject'];
    $title = $row['title'];
    $description = $row['description'];
    $time = $row['time_limit'];
} else {
    echo "<script>alert('Please choose quiz to start.');window.location.href='Overview.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="InstructorEditQuiz.css">
</head>
<body>
    <header>
    <div class="logo">
            <div id="h1">JUST</div><div id="h2">A</div><div id="h3">QUIZ</div>
        </div>
    </header>

    <nav class="navbar">
        <a href="InstructorHome.php">HOME</a>
        <a href="InstructorCreateQuiz.php">CREATE QUIZ</a>
        <a href="Overview.php">OVERVIEW</a>
        <a href="../User/Login.php">LOGOUT</a>
    </nav>

    <main id="questions">
        <div class="main">
            <!-- <div class="flex-container-left">
            <div class="tab">Add Question</div>
            </div> -->
            <div class="flex-container-right">
            <p>CHOOSE QUIZ TYPE</p>
            <div class="container2">
                <div class="toggle">  
                    <input type="radio" id="choice1" name="choice" value="HTML">
                    <label for="choice1">HTML</label>
            
                    <input type="radio" id="choice2" name="choice" value="CSS">
                    <label for="choice2">CSS</label>
            
                    <div id="flap"><span class="content">HTML</span></div>     
                </div>            
            </div> 
            <input type="text" id="quizTitle" placeholder="Type Quiz Title here" value="<?php echo $title; ?>" class="TextBox">
            <input type="text" id="quizDesc" placeholder="Type Quiz Description here" value="<?php echo $description; ?>"  class="TextBox">
            <input type="number" id="quizTime" placeholder="Type Quiz Time Limit (minutes) here" value="<?php echo $time; ?>" class="TextBox">
            </div>
        </div>

        <?php
            $sql = "SELECT * FROM question WHERE quiz_id=$id";
            $result = $conn->query($sql);
        
            if ($result->num_rows > 0) {
                $questionCount = 1;
                while ($questionRow = $result->fetch_assoc()) {
                    $questionid=$questionRow['question_id'];
                    $question=$questionRow['question_text'];
                    echo "<div class='mainQ'>";
                    // echo "<div class='Question-container-left'>";
                    // echo "<div class='tabQ1'>Delete Question</div>";
                    // echo "<div class='tabQ2'>Add Question</div>";
                    // echo "</div>";
                    echo "<div class='Question-container-right'>";
                    echo "<div class='container'>";
                    echo "<span class='Question-num'>Q{$questionCount}</span>";
                    echo "<input type='text' class='Question' placeholder='Type Question Here' value='{$question}'></input>";
                    echo "<div class='Option-box'>";
                    $sql = "SELECT * FROM choices WHERE question_id=$questionid";
                    $Choiceresult = $conn->query($sql);
                    $values=array('A','B','C','D');
                    $selectingChoice=0;
                    while ($choiceRow = $Choiceresult->fetch_assoc()) {
                        $choiceText=$choiceRow['text'];
                        $choiceCorrect=$choiceRow['is_correct'];
                        $optionnum="option_{$questionCount}_{$values[$selectingChoice]}";
                        //echo $optionnum;
                        echo "<input type='text' class='Option' name='option_{$questionCount}_{$values[$selectingChoice]}' placeholder='{$values[$selectingChoice]}. Type Answer Here' value='$choiceText'>";
                        if ($choiceCorrect==1){
                            echo "<input type='radio' name='correct_{$questionCount}' value='{$values[$selectingChoice]}' checked>";
                        }else{
                            echo "<input type='radio' name='correct_{$questionCount}' value='{$values[$selectingChoice]}'>";
                        }
                        $selectingChoice++;
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    $questionCount++;
                }
            } else {
                echo "No quiz available at the moment.";
            }
        ?>
        

        <button id="createQuizBtn" class="Create-Quiz" >Update Quiz</button>

        <script>

            function changeCheckedRadio(value) {
                radioButton = document.querySelector(`input[name="choice"][value="${value}"]`);
                
                if (radioButton) {
                    radioButton.checked = true;

                    flap = document.getElementById("flap");
                    if (flap) {
                        flap.querySelector(".content").textContent = value;
                    }
                } else {
                    console.warn("No radio button found with the value:", value);
                }
            }

            subject='<?php echo $subject; ?>';
            setTimeout(() => changeCheckedRadio(subject), 0);

            //create quiz button
            document.addEventListener('DOMContentLoaded', function() {
                createQuizBtn = document.getElementById('createQuizBtn');
                if (createQuizBtn) {
                    createQuizBtn.addEventListener('click', function() {
                        quizId = <?php echo $id;?>;
                        quizSubject = document.querySelector('input[name="choice"]:checked') ? document.querySelector('input[name="choice"]:checked').value : 'CSS';
                        quizTitle = document.getElementById('quizTitle').value;
                        quizDescription = document.getElementById('quizDesc').value;
                        quizTime = document.getElementById('quizTime').value;

                        questionsData = [];
                        questions = document.querySelectorAll('.mainQ');

                        questions.forEach((questionElement, index) => {
                            questionText = questionElement.querySelector('.Question').value;
                            options = {
                                A: {
                                    text: questionElement.querySelector('input[name="option_' + (index + 1) + '_A"]').value,
                                    iscorrect: questionElement.querySelector('input[name="correct_' + (index + 1) + '"]:checked')?.value === 'A' ? 1 : 0
                                },
                                B: {
                                    text: questionElement.querySelector('input[name="option_' + (index + 1) + '_B"]').value,
                                    iscorrect: questionElement.querySelector('input[name="correct_' + (index + 1) + '"]:checked')?.value === 'B' ? 1 : 0
                                },
                                C: {
                                    text: questionElement.querySelector('input[name="option_' + (index + 1) + '_C"]').value,
                                    iscorrect: questionElement.querySelector('input[name="correct_' + (index + 1) + '"]:checked')?.value === 'C' ? 1 : 0
                                },
                                D: {
                                    text: questionElement.querySelector('input[name="option_' + (index + 1) + '_D"]').value,
                                    iscorrect: questionElement.querySelector('input[name="correct_' + (index + 1) + '"]:checked')?.value === 'D' ? 1 : 0
                                }
                            };

                            questionsData.push({
                                question: questionText,
                                options: options,
                            });

                        });
                        

                        // Send data via AJAX
                        data = {
                            quizId : quizId,
                            quizSubject: quizSubject,
                            quizTitle: quizTitle,
                            quizDescription: quizDescription,
                            quizTime: quizTime,
                            questions: questionsData
                        };

                        fetch('submit_quiz_update.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        // .then(data => {
                        //     alert('Quiz Updated Successfully!');
                        //     window.location.href = 'UserManagement.php';
                        // })
                        // .catch(error => {
                        //     console.error('Error:', error);
                        //     alert('Error updating quiz');
                        // });
                        alert('Quiz Updated Successfully!');
                        window.location.href = 'Overview.php';
                        console.log('Update Quiz button clicked');
                    });
                } else {
                    console.error('Update Quiz button not found');
                }
            });

        </script>

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
    <script src="InstructorEditQuiz.js"></script>
</body>
</html>