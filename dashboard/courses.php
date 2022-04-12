<?php
require_once "partials/top.php";
require_once "../vars/conn.php";
$tab = isset($_GET['tab']) ? $_GET['tab'] : '';

if (!$tab) {
    // Read mode
    $data_list = mysqli_query($conn, "SELECT c.id, c.title, c.image, cat.name as category FROM courses c inner join categories cat on c.kateqoriya = cat.id");
} elseif ($tab === 'create') {
    if (isset($_POST['form_submit'])) {
        $title = trim(mysqli_real_escape_string($conn, $_POST['title']));
        $description = trim(mysqli_real_escape_string($conn, $_POST['description']));
        $category = $_POST['category'];
        $image = $_FILES['image'];
        $file_name = 'courses/' . rand(1, 1000000000) . '-' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $file_name);
        mysqli_query($conn, "INSERT INTO courses(title, description, kateqoriya, image) VALUES ('{$title}', '{$description}', '{$category}' ,'{$file_name}')");
        header('Location: courses.php');
        return;
    }
} elseif ($tab === 'edit') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $updated = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM courses where id = '{$id}'"));
        if (isset($_POST['form_submit'])) {
            $title = trim(mysqli_real_escape_string($conn, $_POST['title']));
            $description = trim(mysqli_real_escape_string($conn, $_POST['description']));
            $category = $_POST['category'];
            $file_name = $updated['image'];
            if (isset($_FILES['image']['name']) and $_FILES['image']['name']) {
                $file_name = 'courses/' . rand(1, 10000000000) . '-' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $file_name);
            }
            if ($title and $file_name and $description and $category) {
                mysqli_query($conn, "UPDATE courses set title = '{$title}', image = '{$file_name}', kateqoriya = '{$category}', description = '{$description}' where id = '{$id}'");
                header('Location: courses.php');
                exit;
            } else {
                header("Location: courses.php?tab=$tab&message=1&id=$id");
                exit;
            }
        }
    } else {
        header('Location: courses.php');
    }
} elseif ($tab == 'delete' and isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM courses where id = $id");
    header('Location: courses.php');
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
                                    <th>Kateqoriya</th>
                                    <th>Şəkil</th>
                                    <th>Düzəliş et</th>
                                    <th>Sil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($data_list)) : ?>
                                    <tr>
                                        <td><?= $row['title'] ?></td>
                                        <td><?= $row['category'] ?></td>
                                        <td>
                                            <img src="../images/<?= $row['image'] ?>" alt="" style="max-width: 150px;">
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
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Description</label>
                            <textarea name="description" class="form-control" required min="50"></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Kateqoriya</label>
                            <select name="category" id="" class="form-control" required>
                                <?php $cats = mysqli_query($conn, "SELECT id, name FROM categories"); ?>
                                <?php while ($cat = mysqli_fetch_assoc($cats)) { ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">İmage</label>
                            <input type="file" required class="form-control" name="image">
                        </div>
                        <button class="btn btn-primary" name="form_submit">Submit</button>
                    </form>
                <?php elseif ($tab == 'edit') : ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label for="">Title</label>
                            <input type="text" class="form-control" name="title" required value="<?= $updated['title'] ?>">
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Description</label>
                            <textarea name="description" class="form-control" rows="10" required min="50"><?= $updated['description'] ?></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">Kateqoriya</label>
                            <select name="category" id="" class="form-control" required>
                                <?php $cats = mysqli_query($conn, "SELECT id, name FROM categories"); ?>
                                <?php while ($cat = mysqli_fetch_assoc($cats)) { ?>
                                    <option value="<?= $cat['id'] ?>" <?php if($cat['id'] == $updated['kateqoriya']) echo 'selected'; ?>><?= $cat['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="">İmage</label>
                            <div style="margin-top: 15px; margin-bottom: 15px">
                                <img src="../images/<?= $updated['image'] ?>" alt="" style="width: 150px;">
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