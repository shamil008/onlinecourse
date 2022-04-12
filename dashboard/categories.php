<?php
require_once "partials/top.php";
require_once "../vars/conn.php";
$tab = isset($_GET['tab']) ? $_GET['tab'] : '';

if (!$tab) {
    // Read mode
    $data_list = mysqli_query($conn, "SELECT * FROM categories");
} elseif ($tab === 'create') {
    if (isset($_POST['form_submit'])) {
        $title = trim(mysqli_real_escape_string($conn, $_POST['title']));
        $image = $_FILES['image'];
        $file_name = 'categories/' . rand(1, 1000000000) . '-' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $file_name);
        mysqli_query($conn, "INSERT INTO categories(name, image) VALUES ('{$title}', '{$file_name}')");
        
        header('Location: categories.php');
        return;
    }
} elseif ($tab === 'edit') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $updated = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM categories where id = '{$id}'"));
        if (isset($_POST['form_submit'])) {
            $name = trim(mysqli_real_escape_string($conn, $_POST['title']));
            $file_name = $updated['image'];
            if(isset($_FILES['image']['name']) and $_FILES['image']['name']) {
                $file_name = 'categories/' . rand(1, 10000000000) . '-'. $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $file_name);
            }
            if ($name and $file_name) {
                mysqli_query($conn, "UPDATE categories set name = '{$name}', image = '{$file_name}' where id = '{$id}'");
                header('Location: categories.php');
                exit;
            } else {
                header("Location: categories.php?tab=$tab&message=1&id=$id");
                exit;
            }
        }
    } else {
        header('Location: categories.php');
    }
} elseif($tab == 'delete' and isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM categories where id = $id");
    header('Location: categories.php');
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
                                    <th>Başlıq</th>
                                    <th>Şəkil</th>
                                    <th>Düzəliş et</th>
                                    <th>Sil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($data_list)) : ?>
                                    <tr>
                                        <td><?= $row['name'] ?></td>
                                        <td>
                                            <img src="../images/<?= $row['image'] ?>" alt="" >
                                        </td>
                                        <td><a href="?tab=edit&id=<?= $row['id'] ?>" class="btn btn-light">Duzelis et</a></td>
                                        <td><a href="?tab=delete&id=<?= $row['id'] ?>" class="btn btn-danger">Sil</a></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php elseif ($tab == 'create') : ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label for="">Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">İmage</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                        <button class="btn btn-primary" name="form_submit">Submit</button>
                    </form>
                <?php elseif ($tab == 'edit') : ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label for="">Title</label> 
                            <input type="text" class="form-control" name="title" value="<?= $updated['name'] ?>">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">İmage</label>
                            <div>
                                <img src="../images/<?= $updated['image'] ?>" alt="">
                            </div>
                            <input type="file" class="form-control" name="image">
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