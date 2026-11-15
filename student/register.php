
<?php

require_once("../db.php");

$conn = get_connection();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password)
    {
        $message = "Password does not match!";
    }
    else
    {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0)
        {
            $message = "Email already registered!";
        }
        else
        {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password, role)
                 VALUES (?, ?, ?, 'student')"
            );

            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute())
            {
                header("location:login.php");
                exit();
            }
            else
            {
                $message = "Registration failed!";
            }
        }
    }
}

?>

<?php include_once("../navbar.php"); ?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white text-center">

                    <h3>Student Registration</h3>

                </div>

                <div class="card-body">

                    <?php if ($message != "") { ?>

                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>

                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">Full Name</label>

                            <input type="text" name="name"
                                class="form-control" required>

                        </div>

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

                        <div class="mb-3">

                            <label class="form-label">Confirm Password</label>

                            <input type="password" name="confirm_password"
                                class="form-control" required>

                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Register
                        </button>

                    </form>

                    <p class="text-center mt-3">

                        Already have an account?

                        <a href="login.php">Login Here</a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include_once("../footer.php"); ?>

<?php $conn->close(); ?>
