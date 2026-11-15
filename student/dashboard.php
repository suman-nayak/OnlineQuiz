
<?php

session_start();

if (!isset($_SESSION['student_id']))
{
    header("location:login.php");
    exit();
}

include_once("../navbar.php");

?>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-body text-center p-5">

            <h2 class="text-primary">
                Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?>!
            </h2>

            <p class="mt-3">
                Welcome to your Student Dashboard.
            </p>

            <p>
                You can participate in available quizzes
                and check your quiz results.
            </p>

            <a href="quiz_start.php" class="btn btn-primary mt-3">
                Start Quiz
            </a>

            <a href="quiz_history.php" class="btn btn-info">
                My Quiz History
            </a>

            <a href="logout.php" class="btn btn-danger mt-3">
                Logout
            </a>

        </div>

    </div>

</div>

<?php include_once("../footer.php"); ?>
