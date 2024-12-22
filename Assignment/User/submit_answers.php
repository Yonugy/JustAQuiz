<?php
include('../../main.php');
include('../session.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the incoming JSON data
    $data = json_decode(file_get_contents('php://input'), true);
    $answers = $data['answers']; // Array of choice IDs

    if (submit_answer($answers, $conn)) {
        $attemptid=$_SESSION['attempt_id'];
        $remain_time = intval($_GET['time']);
        $status=finish_quiz_attempt($attemptid,$remain_time,$conn);
        echo json_encode([
            'success' => true,
            'message' => 'Answers submitted successfully!',
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to submit answers.',
        ]);
    }
} else {
    // Handle invalid request method
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.',
    ]);
}
?>
