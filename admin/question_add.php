
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
    $quiz_id = $_POST['quiz_id'];
    $question = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $marks = $_POST['marks'];

    $stmt = $conn->prepare(
        "INSERT INTO questions
        (quiz_id, question_text, option_a, option_b,
        option_c, option_d, correct_option, marks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "issssssi",
        $quiz_id,
        $question,
        $option_a,
        $option_b,
        $option_c,
        $option_d,
        $correct_option,
        $marks
    );

    if ($stmt->execute())
    {
        $message = "Question added successfully!";
    }
    else
    {
        $message = "Question addition failed!";
    }
}

$result = $conn->query("SELECT * FROM quizzes");

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">
                    <h3>Add MCQ Question</h3>
                </div>

                <div class="card-body">

                    <?php if ($message != "") { ?>
                        <div class="alert alert-info">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">Select Quiz</label>

                            <select name="quiz_id" class="form-select" required>

                                <option value="">Select Quiz</option>

                                <?php while ($row = $result->fetch_assoc()) { ?>

                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Question</label>

                            <textarea name="question_text"
                            class="form-control" required></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Option A</label>

                            <input type="text" name="option_a"
                            class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Option B</label>

                            <input type="text" name="option_b"
                            class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Option C</label>

                            <input type="text" name="option_c"
                            class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Option D</label>

                            <input type="text" name="option_d"
                            class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Correct Option</label>

                            <select name="correct_option"
                            class="form-select" required>

                                <option value="">Select Answer</option>
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Marks</label>

                            <input type="number" name="marks"
                            class="form-control" value="1" min="1" required>

                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Add Question
                        </button>

                    </form>

                    <a href="dashboard.php" class="btn btn-secondary mt-3">
                        Back to Dashboard
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
