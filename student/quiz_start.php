
<?php

session_start();

if (!isset($_SESSION['student_id']))
{
    header("location:login.php");
    exit();
}

require_once("../db.php");

$conn = get_connection();

$result = $conn->query("SELECT * FROM quizzes ORDER BY id DESC");

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5">

    <h2 class="text-primary text-center mb-4">
        Available Quizzes
    </h2>

    <div class="row">

        <?php if ($result->num_rows > 0) { ?>

            <?php while ($row = $result->fetch_assoc()) { ?>

                <div class="col-md-4 mb-4">

                    <div class="card shadow h-100">

                        <div class="card-body">

                            <h4 class="text-primary">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h4>

                            <p>
                                <?php echo htmlspecialchars($row['description']); ?>
                            </p>

                            <p>
                                <b>Duration:</b>
                                <?php echo $row['duration']; ?> Minutes
                            </p>

                            <p>
                                <b>Total Marks:</b>
                                <?php echo $row['total_marks']; ?>
                            </p>

                            <a href="quiz_attempt.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-primary w-100">

                                Start Quiz

                            </a>

                        </div>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="alert alert-info text-center">
                No quizzes available!
            </div>

        <?php } ?>

    </div>

    <a href="dashboard.php" class="btn btn-secondary">
        Back to Dashboard
    </a>

</div>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
