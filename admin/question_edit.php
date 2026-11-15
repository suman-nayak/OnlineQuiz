
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

if (!isset($_GET['id']))
{
    header("location:question_display.php");
    exit();
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $question = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];
    $marks = $_POST['marks'];

    if (
        $question == "" ||
        $option_a == "" ||
        $option_b == "" ||
        $option_c == "" ||
        $option_d == "" ||
        !in_array($correct_option, array("A", "B", "C", "D")) ||
        $marks < 1
    )
    {
        $message = "Please enter valid question details!";
    }
    else
    {
        $stmt = $conn->prepare(
            "UPDATE questions SET
            question_text = ?,
            option_a = ?,
            option_b = ?,
            option_c = ?,
            option_d = ?,
            correct_option = ?,
            marks = ?
            WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssssii",
            $question,
            $option_a,
            $option_b,
            $option_c,
            $option_d,
            $correct_option,
            $marks,
            $id
        );

        if ($stmt->execute())
        {
            $message = "Question updated successfully!";
        }
        else
        {
            $message = "Question update failed!";
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0)
{
    header("location:question_display.php");
    exit();
}

$row = $result->fetch_assoc();

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">

                    <h3>Edit MCQ Question</h3>

                </div>

                <div class="card-body">

                    <?php if ($message != "") { ?>

                        <div class="alert alert-info">
                            <?php echo $message; ?>
                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">Question</label>

                            <textarea name="question_text"
                            class="form-control"
                            rows="3" required><?php echo htmlspecialchars($row['question_text']); ?></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Option A</label>

                            <input type="text" name="option_a"
                            class="form-control"
                            value="<?php echo htmlspecialchars($row['option_a']); ?>"
                            required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Option B</label>

                            <input type="text" name="option_b"
                            class="form-control"
                            value="<?php echo htmlspecialchars($row['option_b']); ?>"
                            required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Option C</label>

                            <input type="text" name="option_c"
                            class="form-control"
                            value="<?php echo htmlspecialchars($row['option_c']); ?>"
                            required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Option D</label>

                            <input type="text" name="option_d"
                            class="form-control"
                            value="<?php echo htmlspecialchars($row['option_d']); ?>"
                            required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Correct Option</label>

                            <select name="correct_option"
                            class="form-select" required>

                                <option value="A"
                                <?php if ($row['correct_option'] == "A") echo "selected"; ?>>
                                    Option A
                                </option>

                                <option value="B"
                                <?php if ($row['correct_option'] == "B") echo "selected"; ?>>
                                    Option B
                                </option>

                                <option value="C"
                                <?php if ($row['correct_option'] == "C") echo "selected"; ?>>
                                    Option C
                                </option>

                                <option value="D"
                                <?php if ($row['correct_option'] == "D") echo "selected"; ?>>
                                    Option D
                                </option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Marks</label>

                            <input type="number" name="marks"
                            class="form-control"
                            min="1"
                            value="<?php echo $row['marks']; ?>"
                            required>

                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Update Question
                        </button>

                    </form>

                    <a href="question_display.php"
                    class="btn btn-secondary mt-3">
                        Back to Questions
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
