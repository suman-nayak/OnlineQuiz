
<?php
include_once "navbar.php";
?>

<!-- Main Section Start -->

<div class="hero">

    <div class="container">

        <h1>Welcome to Online Quiz Application</h1>

        <p class="mt-3">
            Test Your Knowledge and Improve Your Skills
        </p>

        <p>
            Participate in online quizzes, answer questions
            and check your results instantly.
        </p>

        <a href="student/login.php" class="btn btn-primary btn-lg mt-3">
            Start Quiz
        </a>

        <a href="student/register.php" class="btn btn-outline-primary btn-lg mt-3">
            Register Now
        </a>

    </div>

</div>

<!-- Main Section End -->


<!-- Features Section Start -->

<div class="container py-5" id="features">

    <h2 class="section-heading">Our Features</h2>

    <div class="row g-4">

        <!-- Feature 1 -->

        <div class="col-md-4">

            <div class="card feature-card shadow-sm">

                <div class="card-body">

                    <h4 class="text-primary">Multiple Choice Questions</h4>

                    <p>
                        Answer multiple-choice questions
                        and test your knowledge.
                    </p>

                </div>

            </div>

        </div>


        <!-- Feature 2 -->

        <div class="col-md-4">

            <div class="card feature-card shadow-sm">

                <div class="card-body">

                    <h4 class="text-primary">Quiz Timer</h4>

                    <p>
                        Complete your quiz within the
                        given time limit.
                    </p>

                </div>

            </div>

        </div>


        <!-- Feature 3 -->

        <div class="col-md-4">

            <div class="card feature-card shadow-sm">

                <div class="card-body">

                    <h4 class="text-primary">Instant Results</h4>

                    <p>
                        Check your quiz score and
                        results automatically.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Features Section End -->


<!-- About Section Start -->

<div class="container py-4" id="about">

    <div class="about-section shadow-sm">

        <h2 class="section-heading">About Our Application</h2>

        <p class="text-center">

            Online Quiz Application is a web-based platform
            where students can participate in online quizzes.

            Students can register, log in, attempt multiple-choice
            questions and view their results.

            The administrator can manage quizzes and questions.

        </p>

    </div>

</div>

<!-- About Section End -->

<?php
include_once "footer.php";
?>
