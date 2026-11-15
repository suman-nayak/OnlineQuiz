
<?php

session_start();

require_once("../db.php");

$conn = get_connection();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ? AND role = 'student'"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password']))
        {
            $_SESSION['student_id'] = $row['id'];
            $_SESSION['student_name'] = $row['name'];
            $_SESSION['student_email'] = $row['email'];

            header("location:dashboard.php");
            exit();
        }
        else
        {
            $message = "Invalid password!";
        }
    }
    else
    {
        $message = "Student account not found!";
    }
}

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">

                    <h3>Student Login</h3>

                </div>

                <div class="card-body">

                    <?php if ($message != "") { ?>

                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">Email Address</label>

                            <input type="email" name="email"
                                class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Password</label>

                            <input type="password" name="password"
                                class="form-control" required>

                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <p class="text-center mt-3">

                        Don't have an account?

                        <a href="register.php">Register Here</a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
