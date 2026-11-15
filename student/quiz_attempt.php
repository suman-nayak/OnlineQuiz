
<?php

session_start();

if (!isset($_SESSION['student_id']))
{
    header("location:login.php");
    exit();
}

require_once("../db.php");

$conn = get_connection();

if (!isset($_GET['id']))
{
    header("location:quiz_start.php");
    exit();
}

$quiz_id = $_GET['id'];
$student_id = $_SESSION['student_id'];

// Get quiz details

$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0)
{
    die("Quiz not found!");
}

$quiz = $result->fetch_assoc();

// Get questions and calculate actual total marks

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total_questions, SUM(marks) AS total_marks
     FROM questions WHERE quiz_id = ?"
);

$stmt->bind_param("i", $quiz_id);
$stmt->execute();

$question_result = $stmt->get_result();
$question_data = $question_result->fetch_assoc();

if ($question_data['total_questions'] == 0)
{
    die("No questions available for this quiz!");
}

$total_marks = $question_data['total_marks'];

// Check existing active attempt

$stmt = $conn->prepare(
    "SELECT * FROM quiz_attempts
     WHERE user_id = ? AND quiz_id = ? AND status = 'started'
     ORDER BY id DESC LIMIT 1"
);

$stmt->bind_param("ii", $student_id, $quiz_id);
$stmt->execute();

$attempt_result = $stmt->get_result();

if ($attempt_result->num_rows > 0)
{
    $attempt = $attempt_result->fetch_assoc();
    $attempt_id = $attempt['id'];
}
else
{
    // Create new quiz attempt

    $stmt = $conn->prepare(
        "INSERT INTO quiz_attempts
        (user_id, quiz_id, total_marks, status)
        VALUES (?, ?, ?, 'started')"
    );

    $stmt->bind_param("iii", $student_id, $quiz_id, $total_marks);
    $stmt->execute();

    $attempt_id = $conn->insert_id;
}

// Calculate remaining time using server time

$started_time = strtotime($attempt['started_at'] ?? date('Y-m-d H:i:s'));

if (!isset($attempt_id))
{
    die("Unable to start quiz!");
}

if (isset($attempt['started_at']))
{
    $started_time = strtotime($attempt['started_at']);
}

$duration_seconds = $quiz['duration'] * 60;

$remaining_seconds = $duration_seconds - (time() - $started_time);

if ($remaining_seconds <= 0)
{
    $remaining_seconds = 0;
}

// Get quiz questions

$stmt = $conn->prepare(
    "SELECT * FROM questions WHERE quiz_id = ? ORDER BY id ASC"
);

$stmt->bind_param("i", $quiz_id);
$stmt->execute();

$questions = $stmt->get_result();

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5 mb-5">

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            <h3><?php echo htmlspecialchars($quiz['title']); ?></h3>

            <p class="mb-0">
                Total Questions: <?php echo $question_data['total_questions']; ?>
                | Total Marks: <?php echo $total_marks; ?>
            </p>

        </div>

        <div class="card-body">

            <div class="alert alert-warning text-center">

                <h4>
                    Time Remaining:
                    <span id="timer">Loading...</span>
                </h4>

            </div>

            <form method="POST" action="quiz_submit.php"
            id="quizForm"
            onsubmit="return confirm('Are you sure you want to submit the quiz?');">

                <input type="hidden" name="attempt_id"
                value="<?php echo $attempt_id; ?>">

                <?php
                $i = 1;

                while ($row = $questions->fetch_assoc())
                {
                ?>

                    <div class="card mb-4">

                        <div class="card-body">

                            <h5>
                                Q<?php echo $i; ?>.
                                <?php echo htmlspecialchars($row['question_text']); ?>
                            </h5>

                            <p class="text-muted">
                                Marks: <?php echo $row['marks']; ?>
                            </p>

                            <div class="form-check mb-2">

                                <input class="form-check-input"
                                type="radio"
                                name="answers[<?php echo $row['id']; ?>]"
                                value="A"
                                id="q<?php echo $row['id']; ?>a">

                                <label class="form-check-label"
                                for="q<?php echo $row['id']; ?>a">

                                    <?php echo htmlspecialchars($row['option_a']); ?>

                                </label>

                            </div>

                            <div class="form-check mb-2">

                                <input class="form-check-input"
                                type="radio"
                                name="answers[<?php echo $row['id']; ?>]"
                                value="B"
                                id="q<?php echo $row['id']; ?>b">

                                <label class="form-check-label"
                                for="q<?php echo $row['id']; ?>b">

                                    <?php echo htmlspecialchars($row['option_b']); ?>

                                </label>

                            </div>

                            <div class="form-check mb-2">

                                <input class="form-check-input"
                                type="radio"
                                name="answers[<?php echo $row['id']; ?>]"
                                value="C"
                                id="q<?php echo $row['id']; ?>c">

                                <label class="form-check-label"
                                for="q<?php echo $row['id']; ?>c">

                                    <?php echo htmlspecialchars($row['option_c']); ?>

                                </label>

                            </div>

                            <div class="form-check mb-2">

                                <input class="form-check-input"
                                type="radio"
                                name="answers[<?php echo $row['id']; ?>]"
                                value="D"
                                id="q<?php echo $row['id']; ?>d">

                                <label class="form-check-label"
                                for="q<?php echo $row['id']; ?>d">

                                    <?php echo htmlspecialchars($row['option_d']); ?>

                                </label>

                            </div>

                        </div>

                    </div>

                <?php

                    $i++;
                }

                ?>

                <button type="submit" class="btn btn-success w-100">
                    Submit Quiz
                </button>

            </form>

        </div>

    </div>

</div>

<script>
    var remainingSeconds = <?php echo $remaining_seconds; ?>;
</script>

<script src="../js/timer.js"></script>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
