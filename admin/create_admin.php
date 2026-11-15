
<?php

require_once("../db.php");

$conn = get_connection();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0)
    {
        $message = "Email already exists!";
    }
    else
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password, role)
             VALUES (?, ?, ?, 'admin')"
        );

        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute())
        {
            $message = "Admin account created successfully!";
        }
        else
        {
            $message = "Admin account creation failed!";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header bg-dark text-white text-center">
                    <h3>Create Admin Account</h3>
                </div>

                <div class="card-body">

                    <?php if ($message != "") { ?>
                        <div class="alert alert-info">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Admin Name</label>
                            <input type="text" name="name"
                            class="form-control" required>
                        </div>

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
                            Create Admin
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
