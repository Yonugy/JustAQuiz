<?php
include('../../main.php'); // Adjust the path as needed
include('../session.php');

header('Content-Type: application/json'); // Ensure response is JSON

// Get POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

$userId = $data['userId'];
$sql = "DELETE FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
if ($stmt->execute()) {
    echo json_encode(['message' => 'User deleted successfully.']);
} else {
    echo json_encode(['message' => 'Error deleting user.']);
}
?>
