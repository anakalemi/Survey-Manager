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
                    <span class="padding-bottom--15">Create Account</span>

                    <form method="POST" autocomplete="off">
                        <div class="field padding-bottom--24">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" autofocus required>
                        </div>
                        <div class="field padding-bottom--24">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" required>
                        </div>
                        <div class="field padding-bottom--24">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>
                        <div class="field padding-bottom--24">
                            <label for="password-check">Confirm Password</label>
                            <input type="password" name="password-check" id="password-check" required>
                        </div>
                        <div class="field padding-top--15">
                            <input type="submit" name="save" value="Register">
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer-link padding-top--24">
                <span>Already have an account? <a style="color: #5469d4" href="SignIn.php">Sign in</a></span>
                <div class="listing padding-top--24 padding-bottom--24 flex-flex center-center">
                </div>
            </div>

            <?php
            require_once '../controller/UserController.php';

            if (isset($_POST["save"])) {
                if (isset($_POST['name']) && isset($_POST['username'])
                    && isset($_POST['password']) && isset($_POST['password-check'])) {
                    $userController = new UserController();
                    switch ($userController->handleRequests()) {
                        case UserController::USER_ACTION_SUCCESS:
                            echo '<script>window.alert("User registered!")</script>';
                            echo "<script>window.location.href='SignIn.php';</script>";
                            break;
                        case UserController::USER_ACTION_FAIL_DUE_USERNAME_DUB:
                            echo '<script>window.alert("The username you entered already exists.")</script>';
                            break;
                        case UserController::USER_ACTION_FAIL_DUE_PASSWORDS_MISMATCH:
                            echo '<script>window.alert("The passwords you entered do not match.")</script>';
                            break;
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