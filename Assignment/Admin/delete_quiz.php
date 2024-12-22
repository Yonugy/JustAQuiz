<?php
include('../../main.php'); // Adjust the path as needed
include('../session.php');

header('Content-Type: application/json'); // Ensure response is JSON

// Get POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Delete the quiz
$quizId = $data['quizId'];
$sql = "DELETE FROM Quiz WHERE quiz_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quizId);
if ($stmt->execute()) {
    echo json_encode(['message' => 'Quiz deleted successfully.']);
} else {
    echo json_encode(['message' => 'Error deleting quiz.']);
}
?>
