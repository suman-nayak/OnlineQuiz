
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

if (!isset($_GET['id']))
{
    die("Invalid attempt!");
}

$attempt_id = $_GET['id'];

// Check whether this attempt belongs to the student

$stmt = $conn->prepare(
    "SELECT qa.*, q.title
     FROM quiz_attempts qa
     JOIN quizzes q ON qa.quiz_id = q.id
     WHERE qa.id = ? AND qa.user_id = ?
     AND qa.status = 'completed'"
);

$stmt->bind_param("ii", $attempt_id, $student_id);
$stmt->execute();

$attempt_result = $stmt->get_result();

if ($attempt_result->num_rows == 0)
{
    die("Result not found!");
}

$attempt = $attempt_result->fetch_assoc();

// Get all questions and student answers

$stmt = $conn->prepare(
    "SELECT q.*, sa.selected_option, sa.is_correct
     FROM questions q
     LEFT JOIN student_answers sa
     ON q.id = sa.question_id AND sa.attempt_id = ?
     WHERE q.quiz_id = ?
     ORDER BY q.id"
);

$stmt->bind_param("ii", $attempt_id, $attempt['quiz_id']);
$stmt->execute();

$result = $stmt->get_result();

$total_questions = $result->num_rows;
$attempted = 0;
$correct = 0;

include_once("../navbar.php");
?>

<div class="container mt-5">

    <h2 class="text-center mb-3">Answer Review</h2>

    <h5 class="text-center mb-4">
        <?php echo htmlspecialchars($attempt['title']); ?>
    </h5>

    <?php
    // Calculate answer statistics

    $questions = [];

    while ($row = $result->fetch_assoc())
    {
        $questions[] = $row;

        if (!empty($row['selected_option']))
        {
            $attempted++;
        }

        if ($row['is_correct'] == 1)
        {
            $correct++;
        }
    }

    $wrong = $attempted - $correct;
    $unanswered = $total_questions - $attempted;
    ?>

    <div class="row text-center mb-4">

        <div class="col-md-3">
            <div class="card bg-primary text-white p-3 mb-2">
                <h4><?php echo $total_questions; ?></h4>
                Total Questions
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white p-3 mb-2">
                <h4><?php echo $attempted; ?></h4>
                Attempted
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white p-3 mb-2">
                <h4><?php echo $correct; ?></h4>
                Correct
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white p-3 mb-2">
                <h4><?php echo $wrong; ?></h4>
                Wrong
            </div>
        </div>

    </div>

    <div class="alert alert-warning">
        Unanswered Questions: <?php echo $unanswered; ?>
    </div>

    <?php
    $number = 1;

    foreach ($questions as $row)
    {
        $selected = $row['selected_option'];
        $correct_option = $row['correct_option'];
    ?>

    <div class="card shadow mb-4">

        <div class="card-header bg-dark text-white">
            Question <?php echo $number; ?>
        </div>

        <div class="card-body">

            <h5>
                <?php echo htmlspecialchars($row['question_text']); ?>
            </h5>

            <?php
            $options = [
                "A" => $row['option_a'],
                "B" => $row['option_b'],
                "C" => $row['option_c'],
                "D" => $row['option_d']
            ];

            foreach ($options as $key => $value)
            {
                $class = "list-group-item";

                if ($key == $correct_option)
                {
                    $class .= " list-group-item-success";
                }
                else if ($key == $selected)
                {
                    $class .= " list-group-item-danger";
                }
            ?>

                <div class="<?php echo $class; ?>">
                    <strong><?php echo $key; ?>.</strong>
                    <?php echo htmlspecialchars($value); ?>

                    <?php if ($key == $correct_option) { ?>
                        <strong> - Correct Answer</strong>
                    <?php } ?>

                    <?php if ($key == $selected) { ?>
                        <strong> - Your Answer</strong>
                    <?php } ?>

                </div>

            <?php } ?>

            <?php if (empty($selected)) { ?>

                <p class="text-warning mt-3 mb-0">
                    You did not attempt this question.
                </p>

            <?php } ?>

        </div>
    </div>

    <?php
        $number++;
    }
    ?>

    <a href="quiz_history.php" class="btn btn-secondary mb-4">
        Back to Quiz History
    </a>

</div>

<?php
$conn->close();
include_once("../footer.php");
?>
