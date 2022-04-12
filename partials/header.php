<!-- header -->
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    require_once "vars/conn.php";
    $user_id = $_SESSION['user_id'];
    $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users where id = $user_id"));
}
?>
<header>
    <!-- header inner -->
    <div class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                    <div class="full">
                        <div class="center-desk">
                            <div class="logo">
                                <a href="index.php"><img src="temp/images/logo.png" alt="#" /></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                    <nav class="navigation navbar navbar-expand-md navbar-dark ">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarsExample04">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Home</a>
                                </li>
                                <?php if (isset($_SESSION['user_id'])) : ?>
                                    <li class="nav-item d_none">
                                        <a class="nav-link" href="profile.php"><?= $user_data['name'] ?></a>
                                    </li>
                                    <li>
                                        <a href="logout.php" class="nav-link">Logout</a>
                                    </li>
                                <?php else : ?>
                                    <li class="nav-item d_none">
                                        <a class="nav-link" href="login.php">Login</a>
                                    </li>
                                    <li class="nav-item d_none">
                                        <a class="nav-link" href="register.php">Register</a>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- end header inner -->