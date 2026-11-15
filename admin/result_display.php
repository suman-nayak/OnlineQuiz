
<?php
session_start();

if (!isset($_SESSION['admin_id']))
{
    header("location:login.php");
    exit();
}

require_once("../db.php");
$conn = get_connection();

$sql = "SELECT
            qa.id,
            u.name AS student_name,
            u.email,
            q.title,
            qa.score,
            qa.total_marks,
            qa.submitted_at,
            COUNT(sa.id) AS total_questions,
            SUM(CASE WHEN sa.selected_option IS NOT NULL
                AND sa.selected_option != '' THEN 1 ELSE 0 END) AS attempted,
            SUM(CASE WHEN sa.is_correct = 1
                THEN 1 ELSE 0 END) AS correct
        FROM quiz_attempts qa
        JOIN users u ON qa.user_id = u.id
        JOIN quizzes q ON qa.quiz_id = q.id
        LEFT JOIN student_answers sa ON qa.id = sa.attempt_id
        WHERE qa.status = 'completed'
        GROUP BY qa.id
        ORDER BY qa.id DESC";

$result = $conn->query($sql);

include_once("../navbar.php");
?>

<div class="container mt-5">

    <h2 class="text-center mb-4">Quiz Result Management</h2>

    <div class="card shadow">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>Student</th>
                            <th>Email</th>
                            <th>Quiz</th>
                            <th>Score</th>
                            <th>Total Marks</th>
                            <th>Attempted</th>
                            <th>Correct</th>
                            <th>Wrong</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($result->num_rows > 0) { ?>

                        <?php while ($row = $result->fetch_assoc()) {

                            $attempted = $row['attempted'] ?? 0;
                            $correct = $row['correct'] ?? 0;
                            $wrong = $attempted - $correct;

                            $percentage = 0;

                            if ($row['total_marks'] > 0)
                            {
                                $percentage =
                                    ($row['score'] / $row['total_marks']) * 100;
                            }

                        ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($row['student_name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['email']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['title']); ?>
                            </td>

                            <td><?php echo $row['score']; ?></td>

                            <td><?php echo $row['total_marks']; ?></td>

                            <td><?php echo $attempted; ?></td>

                            <td><?php echo $correct; ?></td>

                            <td><?php echo $wrong; ?></td>

                            <td>
                                <?php echo number_format($percentage, 2); ?>%
                            </td>

                            <td>
                                <?php if ($percentage >= 40) { ?>
                                    <span class="badge bg-success">PASS</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">FAIL</span>
                                <?php } ?>
                            </td>

                            <td><?php echo $row['submitted_at']; ?></td>

                        </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="11" class="text-center">
                                No completed quiz results available.
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
