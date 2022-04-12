<?php
include "partials/start.php";
require_once "vars/conn.php";
if (isset($_POST['form_submit'])) {
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if (!$email or !$password) {
        header('Location: login.php?message=1');
        return;
    }
    $password = md5($password);
    $query = mysqli_query($conn, "SELECT * FROM users where email = '{$email}' and password = '{$password}'");
    $row_count = mysqli_num_rows($query);
    if ($row_count > 0) {
        session_start();
        $_SESSION['user_id'] = mysqli_fetch_assoc($query)['id'];
        header('Location: index.php');
        return;
    }
    header('Location: login.php?message=2');
    return;
}

?>

<div class="row">

    <div class="col-md-6 offset-md-3 my-5">
        <?php if (isset($_GET['message'])) : ?>
            <?php if ($_GET['message'] == 1) : ?>
                <div class="alert alert-danger">All fields are required</div>
            <?php elseif ($_GET['message'] == 2) : ?>
                <div class="alert alert-danger">Email or password is incorrect</div>
            <?php endif; ?>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group mb-2">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-group mb-2">
                <label for="">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group my-5 text-center">
                <a href="register.php">Don't have an account yet?</a>
            </div>
            <button class="btn btn-success w-100" name="form_submit">Login</button>
        </form>
    </div>

</div>

</body>

</html>