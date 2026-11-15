
<?php
session_start();

if (!isset($_SESSION['student_id']))
{
    header("location:login.php");
    exit();
}

require_once("../db.php");
$conn = get_connection();

$student_id = $_SESSION['student_id'];

$stmt = $conn->prepare(
    "SELECT qa.id, qa.score, qa.total_marks,
            qa.submitted_at, q.title
     FROM quiz_attempts qa
     JOIN quizzes q ON qa.quiz_id = q.id
     WHERE qa.user_id = ? AND qa.status = 'completed'
     ORDER BY qa.id DESC"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$result = $stmt->get_result();

include_once("../navbar.php");
?>

<div class="container mt-5">

    <h2 class="text-center mb-4">My Quiz History</h2>

    <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>Quiz Name</th>
                            <th>Score</th>
                            <th>Total Marks</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($result->num_rows > 0) { ?>

                        <?php while ($row = $result->fetch_assoc()) {

                            $percentage = 0;

                            if ($row['total_marks'] > 0)
                            {
                                $percentage =
                                    ($row['score'] / $row['total_marks']) * 100;
                            }

                        ?>

                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>

                            <td><?php echo $row['score']; ?></td>

                            <td><?php echo $row['total_marks']; ?></td>

                            <td><?php echo number_format($percentage, 2); ?>%</td>

                            <td>
                                <?php if ($percentage >= 40) { ?>
                                    <span class="badge bg-success">PASS</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">FAIL</span>
                                <?php } ?>
                            </td>

                            <td><?php echo $row['submitted_at']; ?></td>

                            <td>
                                <a href="answer_review.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-primary btn-sm">
                                    Review
                                </a>
                            </td>
                        </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="7" class="text-center">
                                No quiz history available.
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

            <a href="dashboard.php" class="btn btn-secondary">
                Back to Dashboard
            </a>

        </div>
    </div>

</div>

<?php
$conn->close();
include_once("../footer.php");
?>
