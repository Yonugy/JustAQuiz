<?php

session_start();

//connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "justaquiz";

// create & check the connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // will terminate script
}


// Sign Up function
function sign_up($name, $password, $confirm_password, $email, $conn, $role) { // role id: 1 admin, 2 instructor 3 student
    // protect against xss server scripting attack ( not sure if needed)
    $name = htmlspecialchars($name);
    $password = htmlspecialchars($password);
    $email = htmlspecialchars($email);
    $confirm_password = htmlspecialchars($confirm_password);
    if (is_null($role)){
        $role="2";
    }

    // username oni can have alphanumeric, space n underscore
    if (! preg_match("/^[a-zA-Z0-9 _]+$/", $name)){
        return false;
    }

    if (strlen($name) > 15){
        return false;
    }


    // Check if username is unique
    $sql = "SELECT * FROM Users WHERE name = ? OR email = ?";
    $stmt = $conn->prepare($sql);  // use to execute satatement repeatedly with high efficiency
    $stmt->bind_param("ss", $name, $email); // tell dbase what the parameters are, sss means all data type, s string, d double, i int, b blob
    $stmt->execute();
    $result = $stmt->get_result(); // get result with same user name
    if ($result->num_rows > 0) {
        return false;
    }

    if ($password !== $confirm_password) {
        return false;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // prevent sql injection
    $sql = "INSERT INTO Users (role_id, name, email, password_hash) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $role, $name, $email, $hashed_password);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role_id'] = $user['role_id'];
    $_SESSION['username'] = $user['name'];
    $_SESSION['email'] = $user['email'];
}


# login function
function login($email, $password, $conn){
    $username = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc(); // get the result row as an array form, column name with the value
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            return true;
        } else { // wrong password
            return false;
        }
    } else { // not sign up yet
        return false;
    }
}


// Logout function
function logout_user(){ //not used
    session_unset();
    session_destroy();
    return true;
}


// Update User Function (change username or password or email)
function update_user($user_id, $updated_data, $conn){
    $new_name = htmlspecialchars($updated_data['name']);
    $new_password = htmlspecialchars($updated_data['password']);
    $new_email = htmlspecialchars($updated_data['email']);
    $sql = "SELECT * FROM Users WHERE name = ? AND user_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_name, $user_id); // str n int
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return false;
    }

    // Ensure the new email is unique
    $sql = "SELECT * FROM Users WHERE email = ? AND user_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) { //email exists
        return false;
    }

    // Update user information
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE Users SET name = ?, password_hash = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $new_name, $hashed_password, $new_email, $user_id);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Forgot Password Function
function forgot_password($email, $new_password, $conn){
    $email = htmlspecialchars($email);
    $new_password = htmlspecialchars($new_password);
    // Check email exists anot
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE Users SET password_hash = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } else { // email no found
        return false;
    }
}


// create quiz function
function create_quiz($title, $description, $subject, $time_limit,  $conn){
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    $creator_id = $_SESSION['user_id'];
    $sql = "INSERT INTO Quiz (creator_id, title, description, subject, time_limit) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $creator_id, $title, $description, $subject, $time_limit);
    if ($stmt->execute()) {
        $_SESSION['quiz_id'] = $conn->insert_id;
        return true;
    } else {
        return false;
    }
}


// edit quiz function
function edit_quiz($quiz_id, $updated_data, $conn) {
    $title = htmlspecialchars($updated_data[0]);
    $description = htmlspecialchars($updated_data[1]);
    $subject = htmlspecialchars($updated_data[2]);
    $time_limit = htmlspecialchars($updated_data[3]);

    $sql = "UPDATE Quiz SET title = ?, description = ?, subject = ?, time_limit = ? WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $title, $description, $subject, $time_limit, $quiz_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Delete Quiz Function
function delete_quiz($quiz_id, $conn) {
    $sql = "DELETE FROM Quiz WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quiz_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// end quiz create function
function end_quiz_create(){
    if (isset($_SESSION['quiz_id'])) {
        unset($_SESSION['quiz_id']);
        return true;
    } else {
        return false;
    }
}


// Add question function
function add_question($question_text, $conn) {
    if (!isset($_SESSION['quiz_id'])) {
        return false;
    }

    $quiz_id = $_SESSION['quiz_id'];
    $question_text = htmlspecialchars($question_text);

    $sql = "INSERT INTO Question (quiz_id, question_text) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $quiz_id, $question_text);
    if ($stmt->execute()) {
        $id = mysqli_query($conn, "SELECT LAST_INSERT_ID() AS id;");
        $row = mysqli_fetch_array($id);
        return $row['id'];
    } else {
        return false;
    }
}


// Delete Question Function
function delete_question($quiz_id, $question_id, $conn) {
    // Delete the specific question
    $sql = "DELETE FROM Question WHERE question_id = ? AND quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $question_id, $quiz_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Update Question Function
function update_question($question_id, $question_text, $conn) {
    $question_text = htmlspecialchars($question_text);
    $quiz_id = $_SESSION['quiz_id'];
    $sql = "UPDATE Question SET question_text = ? WHERE question_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $question_text, $question_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Add Choice Function
function add_choice($question_id, $choice_text, $is_correct, $conn) {
    $choice_text = htmlspecialchars($choice_text);

    $sql = "INSERT INTO Choices (question_id, text, is_correct) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $question_id, $choice_text, $is_correct);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Edit Choice Function
function edit_choice($choice_id, $new_text, $is_correct, $conn) {
    $new_text = htmlspecialchars($new_text);

    $sql = "UPDATE Choices SET text = ?, is_correct = ? WHERE choice_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $new_text, $is_correct, $choice_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Create Badge Function
function create_badge($creator_id, $achievement_name, $category, $criteria, $badge_file, $conn) {
    $creator_id = $_SESSION['user_id'];
    $achievement_name = htmlspecialchars($achievement_name);
    $category = htmlspecialchars($category);
    $criteria = htmlspecialchars($criteria);
    $badge_image = file_get_contents($badge_file['tmp_name']);
    $image_type = htmlspecialchars($badge_file['type']);
    $sql = "INSERT INTO Badges (creator_id, achievement_name, category, criteria, badge_image, image_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $creator_id, $achievement_name, $category, $criteria, $badge_image, $image_type);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Delete Badge Function
function delete_badge($badge_id, $conn) {
    $sql = "DELETE FROM Badges WHERE badge_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $badge_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Award Badge Function
function award_badge($badge_id, $student_id, $conn) {
    $sql = "SELECT * FROM Collected_Badges WHERE badge_id = ? AND student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $badge_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return false;
    }

    $sql = "INSERT INTO Collected_Badges (badge_id, student_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $badge_id, $student_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Display student obtain badges
function student_obtained_badges($student_id, $conn) {
    $sql = "SELECT b.* FROM Badges b INNER JOIN Collected_Badges cb ON b.badge_id = cb.badge_id WHERE cb.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='badge'>";
            echo "<img src='data:" . htmlspecialchars($row['image_type']) . ";base64," . base64_encode($row['badge_image']) . "' alt='Badge Image' />";
            echo "</div>";
        }
    } else {
        echo "<p>You havnt receive any badges yet. Keep it up !</p>";
    }
}


// Start Quiz Attempt Function
function start_quiz_attempt($quiz_id, $conn) {
    $student_id = $_SESSION['user_id'];
    $stat = 'in_progress';

    $sql = "INSERT INTO Attempt (student_id, quiz_id, stat) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $student_id, $quiz_id, $stat);
    if ($stmt->execute()) {
        $_SESSION['attempt_id'] = $conn->insert_id; // attempting the quiz now
        # echo timer function here (js)
        return true;
    } else {
        # echo // javascript alert box here
        return false;
    }
}


// Finish Quiz Attempt Function
function finish_quiz_attempt($attempt_id, $time_remaining, $conn) {
    $stat = 'completed';
    $feedback = '-';

    $sql = "UPDATE Attempt SET stat = ? WHERE attempt_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $stat, $attempt_id);
    if ($stmt->execute()) { // change attempt table first
        $sql = "INSERT INTO Result (attempt_id, time_remaining, feedback) VALUES (?, ? , ? )";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $attempt_id, $time_remaining, $feedback);
        if ($stmt->execute()) { // then change result table
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// User Profile Function
function user_profile($conn, $data) {
    $user_id=$_SESSION['user_id'];
    $sql = "SELECT $data FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $info = htmlspecialchars($row[$data]);
        return $info;
    } else {
        echo "<script>console.log('Error.')</script>";
        return false;
    }
}



// Calculate Used Time Function
function calculate_used_time($attempt_id, $conn) {
    $sql = "SELECT q.time_limit, r.time_remaining FROM Result r INNER JOIN Attempt a ON r.attempt_id = a.attempt_id INNER JOIN Quiz q ON a.quiz_id = q.quiz_id WHERE r.attempt_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attempt_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $used_time = $row['time_limit']*60 - $row['time_remaining'];
        return $used_time/60;
    } else {
        # echo // javascript alert box here
    }
}


// Student total collected badges
function calculate_total_badges_collected($student_id, $conn) {
    $sql = "SELECT COUNT(*) AS total FROM Collected_Badges WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = htmlspecialchars($row['total']);
    } else {
        $total = 0;
    }
    return $total;
}


// Submit Answer Function
function submit_answer($answers, $conn) {
    $attempt_id = $_SESSION['attempt_id'];
    foreach ($answers as $choice_id) {
        $attempt_id = htmlspecialchars($attempt_id);
        $choice_id = htmlspecialchars($choice_id);

        $sql = "INSERT INTO Student_Answer (attempt_id, choice_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $attempt_id, $choice_id);
        if (!$stmt->execute()) {
            # echo // javascript alert box here
            return false;
        }
    }

    # echo // javascript alert box here (answer submitted liao)
    return true;
}


// feedback function
function write_feedback($result_id, $feedback, $conn) {
    $feedback = htmlspecialchars($feedback);
 
    $sql = "UPDATE result SET feedback = ? WHERE result_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $feedback, $result_id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


// Calculate score function (add liao attempt id in table)
function calculate_score($attempt_id, $conn){
    $sql = "SELECT c.question_id, c.is_correct FROM Student_Answer sa INNER JOIN Choices c ON sa.choice_id = c.choice_id WHERE sa.attempt_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $attempt_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $score = 0;
    while ($row = $result->fetch_assoc()) {
        if ($row['is_correct']) {
            $score++;
        }
    }

    return $score;

}


// Calculate Total Quiz Done by the Student Function
function total_quiz_done($student_id, $conn, $subject="") {
    if ($subject!=""){
        $sql = "SELECT COUNT(DISTINCT a.quiz_id) AS total FROM Attempt a INNER JOIN Quiz q ON a.quiz_id = q.quiz_id WHERE a.student_id = ? AND a.stat = 'completed' AND q.subject = '$subject'";

    }else{
        $sql = "SELECT COUNT(DISTINCT quiz_id) AS total FROM Attempt WHERE student_id = ? AND stat = 'completed'";

    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}


// Grade Function
function calculate_grade($score, $total_questions) {
    $percentage = ($score / $total_questions) * 100;
    if ($percentage > 80) {
        return 'A';
    } elseif ($percentage > 70) {
        return 'B';
    } elseif ($percentage > 60) {
        return 'C';
    } elseif ($percentage > 50) {
        return 'D';
    } elseif ($percentage > 40) {
        return 'E';
    } else {
        return 'F';
    }
}


// Total Question Function
function total_question($quiz_id, $conn){
    $sql = "SELECT COUNT(*) AS totalq FROM Question WHERE quiz_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['totalq'];
    } else {
        return 0;
    }
}

// Calculate Total Student Function
function total_student($conn) {
    $sql = "SELECT COUNT(*) AS total FROM Users WHERE role_id = 3";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}


// Calculate Total Instructor Function
function total_instructor($conn) {
    $sql = "SELECT COUNT(*) AS total FROM Users WHERE role_id = 2";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}


// Calculate Total Quiz Created by an Instructor Function
function calculate_total_quiz_created($conn) {
    $creator_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) AS total FROM Quiz WHERE creator_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $creator_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}


// Get Overall Report Function
function overall_report($conn) {
    $student_id = $_SESSION['user_id'];

    $total_score_percent = 0;
    $total_attempts = 0;
    $total_quiz_completed = total_quiz_done($student_id, $conn);

    //get each attempt score
    $sql = "SELECT a.attempt_id, SUM(c.is_correct) AS score, COUNT(q.question_id) AS total_ques FROM Attempt a
        INNER JOIN Question q ON q.quiz_id = a.quiz_id
        INNER JOIN Student_Answer sa ON sa.attempt_id = a.attempt_id
        INNER JOIN Choices c ON sa.choice_id = c.choice_id
        WHERE a.student_id = ? AND a.stat = 'completed'
        GROUP BY a.attempt_id";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $score = $row['score'];
            $total_questions = $row['total_ques'];
            $percent = ($score / $total_questions) * 100;
            $total_score_percent += $percent;
            $total_attempts++;
        }
    }

    $average_score = round($total_score_percent / $total_attempts, 2);
    if ($average_score > 80) {
        $average_grade = 'A';
    } elseif ($average_score > 70) {
        $average_grade = 'B';
    } elseif ($average_score > 60) {
        $average_grade = 'C';
    } elseif ($average_score > 50) {
        $average_grade = 'D';
    } elseif ($average_score > 40) {
        $average_grade = 'E';
    } else {
        $average_grade = 'F';
    }

    return array($average_score, $average_grade, $total_quiz_completed); //cannot return multiple value 1
}


// total quiz attempt by student craeted by 1 specific instructor function
function total_quiz_attempt($conn){
    $creator_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) AS total FROM Attempt a INNER JOIN Quiz q ON a.quiz_id = q.quiz_id WHERE q.creator_id = ?";
    $stmt = $conn-> prepare($sql);
    $stmt->bind_param("i", $creator_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}


// display all student info
function admin_students_info($conn) { //used in user management
    $sql = "SELECT user_id, name FROM Users WHERE role_id = 3";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $student_id = $row['user_id'];
            $student_name = $row['name'];
            $badges_collected = calculate_total_badges_collected($student_id, $conn);
            $quiz_completed = total_quiz_done($student_id, $conn);

            // return $student_id, $student_name, $badges_collected, $quiz_completed; cant return many value

        }
    } else {
        echo "No student found.";
    }
}


function admin_instructors_info($conn) {
    $sql = "SELECT user_id, name FROM Users WHERE role_id = 2";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $instructor_id = $row['user_id'];
            $instructor_name = $row['name'];

            $sql_quiz_count = "SELECT COUNT(*) AS total FROM Quiz WHERE creator_id = ?";
            $stmt = $conn->prepare($sql_quiz_count);
            $stmt->bind_param("i", $instructor_id);
            $stmt->execute();
            $result_quiz_count = $stmt->get_result();
            $total_quiz_create = 0;
            if ($result_quiz_count->num_rows > 0) {
                $row_quiz_count = $result_quiz_count->fetch_assoc();
                $total_quiz_create = $row_quiz_count['total'];
            }
        }
    } else {
        echo "No instructors found.";
    }
}


function calculate_total_badges_created($conn) {
    $creator_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) AS total FROM badges WHERE creator_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $creator_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return 0;
    }
}


function getTodayDate($format = "M j, Y") {
    return date($format);
}

?>