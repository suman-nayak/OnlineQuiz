
<?php

session_start();

if (!isset($_SESSION['admin_id']))
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
        All Quizzes
    </h2>

    <a href="quiz_add.php" class="btn btn-primary mb-3">
        Add New Quiz
    </a>

    <table class="table table-bordered table-striped">

        <thead class="table-dark">

            <tr>
                <th>ID</th>
                <th>Quiz Title</th>
                <th>Duration</th>
                <th>Total Marks</th>
                <th>Created At</th>
            </tr>

        </thead>

        <tbody>

            <?php while ($row = $result->fetch_assoc()) { ?>

                <tr>

                    <td><?php echo $row['id']; ?></td>

                    <td><?php echo htmlspecialchars($row['title']); ?></td>

                    <td><?php echo $row['duration']; ?> Minutes</td>

                    <td><?php echo $row['total_marks']; ?></td>

                    <td><?php echo $row['created_at']; ?></td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

    <a href="dashboard.php" class="btn btn-secondary">
        Back to Dashboard
    </a>

</div>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
