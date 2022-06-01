<?php session_start(); ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Survey Manager</title>
    <link rel="stylesheet" type="text/css" href="../asset/css/style.css">
    <link rel="stylesheet" type="text/css" href="../asset/css/background-animation.css">

</head>

<body>
<div class="login-root">
    <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
        <?php include 'reusable/BackgroundAnimation.php' ?>
        <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
            <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
                <h1>Survey Manager</h1>
            </div>
            <div class="formbg">
                <div class="padding-horizontal--48">
                    <span class="padding-bottom--15">Log in to your account</span>
                    <form method="POST" autocomplete="off">
                        <div class="field padding-bottom--24">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" required autofocus>
                        </div>
                        <div class="field padding-bottom--24">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                        <div class="field padding-top--15">
                            <input type="submit" name="authenticate" value="Log in">
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer-link padding-top--24">
                <span>Don't have an account? <a style="color: #5469d4" href="SignUp.php">Sign up</a></span>
                <div class="listing padding-top--24 padding-bottom--24 flex-flex center-center">
                    <span><a href="UnregisteredHome.php">Continue unregistered</a></span>
                </div>
            </div>
            <?php
            include '../controller/UserController.php';
            if (isset($_POST["authenticate"])) {
                if (isset($_POST['username']) && isset($_POST['password'])) {
                    $userController = new UserController();
                    $loggedUser = $userController->handleRequests();

                    if ($loggedUser !== null) {
                        $_SESSION['loggedUser'] = $loggedUser;
                        echo "<script>window.location.href='Home.php';</script>";
                    } else {
                        echo "<script>window.alert('Incorrect username or password');</script>";
                    }
                    exit();
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>