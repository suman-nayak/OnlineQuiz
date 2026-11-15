
<?php

session_start();

if (!isset($_SESSION['admin_id']))
{
    header("location:login.php");
    exit();
}

require_once("../db.php");

$conn = get_connection();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");

    $stmt->bind_param("i", $id);

    if ($stmt->execute())
    {
        $message = "Question deleted successfully!";
    }
    else
    {
        $message = "Question deletion failed!";
    }
}

$result = $conn->query(
    "SELECT questions.*, quizzes.title
     FROM questions
     INNER JOIN quizzes
     ON questions.quiz_id = quizzes.id
     ORDER BY questions.id DESC"
);

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5">

    <h2 class="text-primary text-center mb-4">
        All Questions
    </h2>

    <?php if ($message != "") { ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <a href="question_add.php" class="btn btn-primary mb-3">
        Add New Question
    </a>

    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Quiz</th>
                    <th>Question</th>
                    <th>Correct Option</th>
                    <th>Marks</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

                <?php while ($row = $result->fetch_assoc()) { ?>

                    <tr>

                        <td><?php echo $row['id']; ?></td>

                        <td><?php echo htmlspecialchars($row['title']); ?></td>

                        <td><?php echo htmlspecialchars($row['question_text']); ?></td>

                        <td><?php echo $row['correct_option']; ?></td>

                        <td><?php echo $row['marks']; ?></td>

                        <td>
                            <a href="question_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            
                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question?');">

                                 <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>

                            </form>

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

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
