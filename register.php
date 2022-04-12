<?php
include "partials/start.php";
require_once "vars/conn.php";
if (isset($_POST['form-submit'])) {
    $name = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if ($name and $email and $password) {
        $row_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users where email = '{$email}'"));
        if ($row_count > 0) {
            header("Location: register.php?message=2");
            exit;
        }
        $password = md5($password);
        mysqli_query($conn, "INSERT INTO users(name, email, password) VALUES ('{$name}', '{$email}', '{$password}')");
        session_start();
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        header('Location: index.php');
        return;
    } else {
        header("Location: register.php?message=1");
        exit;
    }
}
?>

<div class="row">

    <div class="col-md-6 offset-md-3 my-5">
        <?php if (isset($_GET['message'])) : ?>
            <?php if ($_GET['message'] == 1) : ?>
                <div class="alert alert-danger">All fields are required</div>
            <?php elseif ($_GET['message'] == 2) : ?>
                <div class="alert alert-danger">Email already taken</div>
            <?php endif; ?>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group mb-2">
                <label for="">Username</label>
                <input type="text" class="form-control" name="username">
            </div>
            <div class="form-group mb-2">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-group mb-2">
                <label for="">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group my-5 text-center">
                <a href="login.php">Already have an account?</a>
            </div>
            <button class="btn btn-success w-100" name="form-submit">Create account</button>
        </form>
    </div>

</div>

</body>

</html>