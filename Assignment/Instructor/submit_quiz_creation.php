<?php
include('../../main.php');
include('../session.php');

// Get raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Access quiz title, description, and questions
$quizSubject = $data['quizSubject'];
$quizTitle = $data['quizTitle'];
$quizDescription = $data['quizDescription'];
$time_limit = $data['quizTime'];
$questions = $data['questions'];

// Prepare the query to insert quiz details (assuming you have a quiz table)
create_quiz($quizTitle, $quizDescription, $quizSubject, $time_limit,  $conn);

// Insert questions and choices
foreach ($questions as $question) {
    $qid=add_question($question['question'], $conn);
    foreach ($question['options'] as $choice => $option) {
        add_choice($qid, $option['text'], $option['iscorrect'], $conn);
    }
}

end_quiz_create();

echo json_encode(['status' => 'success', 'message' => 'Quiz created successfully']);
?>
