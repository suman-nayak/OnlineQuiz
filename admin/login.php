
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
        "SELECT * FROM users WHERE email = ? AND role = 'admin'"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password']))
        {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];

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
        $message = "Admin account not found!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header bg-dark text-white text-center">
                    <h3>Admin Login</h3>
                </div>

                <div class="card-body">

                    <?php if ($message != "") { ?>
                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">Email</label>

                            <input type="email" name="email"
                            class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Password</label>

                            <input type="password" name="password"
                            class="form-control" required>

                        </div>

                        <button type="submit" class="btn btn-dark w-100">
                            Login
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>

<?php $conn->close(); ?>
