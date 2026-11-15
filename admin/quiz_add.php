
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
    $title = $_POST['title'];
    $description = $_POST['description'];
    $duration = $_POST['duration'];
    $total_marks = $_POST['total_marks'];
    $admin_id = $_SESSION['admin_id'];

    $stmt = $conn->prepare(
        "INSERT INTO quizzes
        (title, description, duration, total_marks, created_by)
        VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssiii",
        $title,
        $description,
        $duration,
        $total_marks,
        $admin_id
    );

    if ($stmt->execute())
    {
        echo "<script>alert('Quiz created successfully!');</script>";
    }
    else
    {
        echo "<script>alert('Unable to create quiz!');</script>";
    }
}

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">
                    <h3>Add New Quiz</h3>
                </div>

                <div class="card-body">

                    <?php if ($message != "") { ?>
                        <div class="alert alert-info">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">Quiz Title</label>

                            <input type="text" name="title"
                            class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Description</label>

                            <textarea name="description"
                            class="form-control" required></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Duration (Minutes)</label>

                            <input type="number" name="duration"
                            class="form-control" min="1" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Total Marks</label>

                            <input type="number" name="total_marks"
                            class="form-control" min="1" required>

                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Create Quiz
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
