<?php
include('../../main.php');
include('../session.php');

// Get raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Access quiz title, description, and questions
$quizId = $data['quizId'];
$quizSubject = $data['quizSubject'];
$quizTitle = $data['quizTitle'];
$quizDescription = $data['quizDescription'];
$time_limit = $data['quizTime'];
$questions = $data['questions'];
$amount=total_question($quizId, $conn);
// $amount=count($data['questions']);
$updated_data=array($quizTitle,$quizDescription,$quizSubject,$time_limit);
edit_quiz($quizId, $updated_data, $conn);

$sql = "SELECT * FROM question WHERE quiz_id=$quizId"; //10 questions
$result = $conn->query($sql);

$count=1;
foreach ($questions as $question) { //received questions, 11 questions
    if (!($count > $amount)){
        $questionRow = $result->fetch_assoc();
        $question_id=$questionRow['question_id'];
        $question_text=$questionRow['question_text'];
        update_question($question_id, $question['question'], $conn);
    }else{
        $question_id=add_question($question['question'], $conn);
    }
    $choiceSql = "SELECT * FROM choices WHERE question_id=$question_id"; //10 questions
    $choiceResult = $conn->query($choiceSql);
    foreach ($question['options'] as $choice => $option){
        $choiceRow = $choiceResult->fetch_assoc();
        $choice_id=$choiceRow['choice_id'];
        if (!($count > $amount)){
            edit_choice($choice_id, $option['text'], $option['iscorrect'], $conn);
        }else{
            add_choice($quizId, $option['text'], $option['iscorrect'], $conn);
        }
    }
    $count++;
}

echo json_encode(['status' => 'success', 'message' => 'Quiz Updated successfully']);
?>
