<?php
require_once "partials/top.php";
require_once "../vars/conn.php";
$tab = isset($_GET['tab']) ? $_GET['tab'] : '';

if (!$tab) {
    // Read mode
    $users = mysqli_query($conn, "SELECT * FROM users");
} elseif ($tab === 'create') {
    if (isset($_POST['form_submit'])) {
        $name = trim(mysqli_real_escape_string($conn, $_POST['username']));
        $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $role = $_POST['role'];
        if ($name and $email and $password and $role) {
            $row_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users where email = '{$email}'"));
            if ($row_count > 0) {
                header("Location: users.php?tab=$tab&message=2");
                exit;
            }
            $password = md5($password);

            mysqli_query($conn, "INSERT INTO users(name, email, password, role) VALUES ('{$name}', '{$email}', '{$password}', '{$role}')");
            header('Location: users.php');
        } else {
            header("Location: users.php?tab=$tab&message=1");
            exit;
        }
    }
} elseif ($tab === 'edit') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $updated = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users where id = '{$id}'"));
        if (isset($_POST['form_submit'])) {
            $name = trim(mysqli_real_escape_string($conn, $_POST['username']));
            $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $role = $_POST['role'];
            if ($name and $email and $password and $role) {
                if ($email !== $updated['email']) {
                    $row_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users where email = '{$email}'"));
                    if ($row_count > 0) {
                        header("Location: users.php?tab=$tab&message=2&id=$id");
                        exit;
                    }
                }
                $password = md5($password);
                mysqli_query($conn, "UPDATE users set name = '{$name}', email = '{$email}', password ='{$password}', role ='{$role}' where id = '{$id}'");
                header('Location: users.php');
                exit;
            } else {
                header("Location: users.php?tab=$tab&message=1&id=$id");
                exit;
            }
        }
    } else {
        header('Location: users.php');
    }
} elseif($tab == 'delete' and isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM users where id = $id");
    header('Location: users.php');
    exit;
}

?>
<div class="d-flex" id="wrapper">
    <!-- Sidebar-->
    <?php
    require_once "partials/left.php";
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php if (isset($_GET['message'])) : ?>
                <?php if ($_GET['message'] == 1) : ?>
                    <div class="alert alert-danger">All fields are required</div>
                <?php elseif ($_GET['message'] == 2) : ?>
                    <div class="alert alert-danger">This email already taken</div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="col-12">
                <?php if (!$tab) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Qeydiyyat tarixi</th>
                                    <th>Düzəliş et</th>
                                    <th>Sil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($users)) : ?>
                                    <tr>
                                        <td><?= $row['name'] ?></td>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['role'] ?></td>
                                        <td><?= $row['tarix'] ?></td>
                                        <td><a href="?tab=edit&id=<?= $row['id'] ?>" class="btn btn-light">Duzelis et</a></td>
                                        <td><a href="?tab=delete&id=<?= $row['id'] ?>" class="btn btn-danger">Sil</a></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php elseif ($tab == 'create') : ?>
                    <form action="" method="POST">
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
                            <input type="password" required name="password" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Role</label>
                            <select name="role" id="" class="form-control">
                                <option value="normal_user">Adi</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" name="form_submit">Submit</button>
                    </form>
                <?php elseif ($tab == 'edit') : ?>

                    <form action="" method="POST">
                        <div class="form-group mb-2">
                            <label for="">Username</label>
                            <input type="text" class="form-control" name="username" value="<?= $updated['name'] ?>">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= $updated['email'] ?>">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Password</label>
                            <input type="password" required name="password" class="form-control">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Role</label>
                            <select name="role" id="" class="form-control">
                                <option value="normal_user">Adi</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" name="form_submit">Submit</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
    require_once "partials/bottom.php";
    ?>