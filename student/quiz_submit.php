
<?php

session_start();

if (!isset($_SESSION['student_id']))
{
    header("location:login.php");
    exit();
}

require_once("../db.php");

$conn = get_connection();

if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    header("location:quiz_start.php");
    exit();
}

$attempt_id = $_POST['attempt_id'];
$student_id = $_SESSION['student_id'];

// Check quiz attempt

$stmt = $conn->prepare(
    "SELECT * FROM quiz_attempts
     WHERE id = ? AND user_id = ? AND status = 'started'"
);

$stmt->bind_param("ii", $attempt_id, $student_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0)
{
    die("Invalid or already submitted quiz attempt!");
}

$attempt = $result->fetch_assoc();

$quiz_id = $attempt['quiz_id'];

$score = 0;

// Get all questions of this quiz

$stmt = $conn->prepare(
    "SELECT * FROM questions WHERE quiz_id = ?"
);

$stmt->bind_param("i", $quiz_id);
$stmt->execute();

$questions = $stmt->get_result();

// Start database transaction

$conn->begin_transaction();

try
{
    while ($row = $questions->fetch_assoc())
    {
        $question_id = $row['id'];

        $selected_option = null;

        if (
            isset($_POST['answers'][$question_id]) &&
            in_array($_POST['answers'][$question_id], array("A", "B", "C", "D"))
        )
        {
            $selected_option = $_POST['answers'][$question_id];
        }

        $is_correct = 0;

        if ($selected_option == $row['correct_option'])
        {
            $is_correct = 1;

            $score = $score + $row['marks'];
        }

        // Save student answer

        $insert = $conn->prepare(
            "INSERT INTO student_answers
            (attempt_id, question_id, selected_option, is_correct)
            VALUES (?, ?, ?, ?)"
        );

        $insert->bind_param(
            "iisi",
            $attempt_id,
            $question_id,
            $selected_option,
            $is_correct
        );

        $insert->execute();
    }

    // Update quiz attempt

    $update = $conn->prepare(
        "UPDATE quiz_attempts
         SET score = ?, submitted_at = NOW(), status = 'completed'
         WHERE id = ? AND user_id = ? AND status = 'started'"
    );

    $update->bind_param("iii", $score, $attempt_id, $student_id);

    $update->execute();

    $conn->commit();

    header("location:result.php?id=" . $attempt_id);
    exit();
}
catch (Exception $e)
{
    $conn->rollback();

    die("Quiz submission failed: " . $e->getMessage());
}

?>
