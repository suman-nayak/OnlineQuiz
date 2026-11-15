
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

$attempt_id = $_GET['id'];
$student_id = $_SESSION['student_id'];

$stmt = $conn->prepare(
    "SELECT quiz_attempts.*, quizzes.title
     FROM quiz_attempts
     INNER JOIN quizzes
     ON quiz_attempts.quiz_id = quizzes.id
     WHERE quiz_attempts.id = ?
     AND quiz_attempts.user_id = ?
     AND quiz_attempts.status = 'completed'"
);

$stmt->bind_param("ii", $attempt_id, $student_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0)
{
    die("Result not found!");
}

$row = $result->fetch_assoc();

$score = $row['score'];
$total_marks = $row['total_marks'];

if ($total_marks > 0)
{
    $percentage = ($score / $total_marks) * 100;
}
else
{
    $percentage = 0;
}

if ($percentage >= 40)
{
    $status = "PASS";
    $color = "success";
}
else
{
    $status = "FAIL";
    $color = "danger";
}

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow text-center">

                <div class="card-header bg-primary text-white">

                    <h3>Quiz Result</h3>

                </div>

                <div class="card-body p-5">

                    <h4 class="text-primary">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h4>

                    <hr>

                    <h5>
                        Student Name:
                        <?php echo htmlspecialchars($_SESSION['student_name']); ?>
                    </h5>

                    <h2 class="mt-4">
                        Your Score
                    </h2>

                    <h1 class="text-primary">
                        <?php echo $score; ?> /
                        <?php echo $total_marks; ?>
                    </h1>

                    <h4 class="mt-3">
                        Percentage:
                        <?php echo number_format($percentage, 2); ?>%
                    </h4>

                    <h3 class="mt-3 text-<?php echo $color; ?>">

                        <?php echo $status; ?>

                    </h3>

                    <hr>

                    <a href="quiz_start.php" class="btn btn-primary mt-3">
                        Try Another Quiz
                    </a>

                    <a href="dashboard.php" class="btn btn-secondary mt-3">
                        Dashboard
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
