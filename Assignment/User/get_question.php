<?php
include('../../main.php');
include('../session.php');

if (isset($_GET['id']) && isset($_GET['q'])) {
    $quizid = intval($_GET['id']);
    $qnum = intval($_GET['q']);
    $amount = total_question($quizid, $conn);
    
    // Fetch question
    $questions = mysqli_query($conn, "SELECT * FROM question WHERE quiz_id=$quizid");
    $allRows = $questions->fetch_all(MYSQLI_ASSOC);
    $row = $allRows[$qnum]; // Get the specific question
    $qid = $row['question_id'];
    $question_text = $row['question_text'];

    // Fetch choices
    $choices = mysqli_query($conn, "SELECT * FROM choices WHERE question_id=$qid");
    $choiceArray = array();
    $choiceidArray = array();
    while ($row = mysqli_fetch_array($choices)) {
        array_push($choiceArray, $row['text']);
        array_push($choiceidArray, $row['choice_id']);
    }


    // Return data as JSON
    echo json_encode([
        'question' => $question_text,
        'choices' => $choiceArray,
        'values' => $choiceidArray,
        'qnum' => $qnum + 1,
        'amount' => $amount
    ]);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
