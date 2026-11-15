
<?php

session_start();

if (!isset($_SESSION['admin_id']))
{
    header("location:login.php");
    exit();
}

require_once("../db.php");

$conn = get_connection();

$result = $conn->query("SELECT COUNT(*) AS total FROM quizzes");
$quiz = $result->fetch_assoc();

$result = $conn->query("SELECT COUNT(*) AS total FROM questions");
$question = $result->fetch_assoc();

$result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='student'");
$student = $result->fetch_assoc();

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5">

    <h2 class="text-primary">
        Welcome Admin, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!
    </h2>

    <p>Manage your online quiz application from here.</p>

    <div class="row mt-4">

        <div class="col-md-4 mb-3">

            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <h4>Total Quizzes</h4>
                    <h2><?php echo $quiz['total']; ?></h2>
                </div>
            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card bg-success text-white shadow">
                <div class="card-body text-center">
                    <h4>Total Questions</h4>
                    <h2><?php echo $question['total']; ?></h2>
                </div>
            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card bg-warning text-dark shadow">
                <div class="card-body text-center">
                    <h4>Total Students</h4>
                    <h2><?php echo $student['total']; ?></h2>
                </div>
            </div>

        </div>

    </div>

    <div class="card shadow mt-4">

        <div class="card-body">

            <h4>Admin Menu</h4>

            <a href="quiz_add.php" class="btn btn-primary m-2">
                Add Quiz
            </a>

            <a href="quiz_display.php" class="btn btn-success m-2">
                View Quizzes
            </a>

            <a href="question_add.php" class="btn btn-info m-2">
                Add Questions
            </a>

            <a href="question_display.php" class="btn btn-warning m-2">
                View Questions
            </a>

            <a href="result_display.php" class="btn btn-dark m-2">
                View Results
            </a>

            <a href="logout.php" class="btn btn-danger m-2">
                Logout
            </a>

        </div>

    </div>

</div>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
