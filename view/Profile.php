<?php
require_once '../model/User.php';
session_start();
?>
<?php
$currentUser = $_SESSION['loggedUser'];
//Go back to Sign In page if no user is logged in
if (empty($currentUser) || $currentUser->getUserId() == null) {
    header("Location: SignIn.php");
    exit();
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Survey Manager</title>
    <link rel="stylesheet" type="text/css" href="../asset/css/style.css">
    <link rel="stylesheet" type="text/css" href="../asset/css/nav-bar.css">

</head>

<body>
<?php include 'reusable/NavBar.php' ?>

<div class="login-root">
    <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
        <div class="box-root padding-top--24 padding-bottom--24 flex-flex flex-direction--column"
             style="flex-grow: 1; z-index: 9;">
            <div class="formbg">
                <div class="padding-horizontal--48">
                    <span class="padding-bottom--15">Edit Profile</span>
                    <form method="POST" autocomplete="off">
                        <div class="field padding-bottom--24">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" value="<?php echo $currentUser->getName(); ?>"
                                   required>
                        </div>
                        <div class="field padding-bottom--24">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username"
                                   value="<?php echo $currentUser->getUsername(); ?>" required>
                        </div>
                        <div class="field padding-bottom--24">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password"
                                   value="<?php echo $currentUser->getPassword(); ?>" required>
                        </div>
                        <div class="field padding-bottom--24">
                            <label for="password-check">Confirm Password</label>
                            <input type="password" name="password-check" id="password-check"
                                   value="<?php echo $currentUser->getPassword(); ?>" required>
                        </div>
                        <div class="field padding-top--15">
                            <input type="submit" name="update" value="Save">
                        </div>
                    </form>
                </div>
            </div>

            <?php
            require_once '../controller/UserController.php';
            if (isset($_POST["update"])) {
                if (isset($_POST['name']) && isset($_POST['username'])
                    && isset($_POST['password']) && isset($_POST['password-check'])) {

                    $userController = new UserController();
                    $userController->handleRequests();

                    switch ($userController->handleRequests()) {
                        case UserController::USER_ACTION_SUCCESS:
                            echo '<script>window.alert("Changes Saved!")</script>';
                            echo "<script>window.location.href='Home.php';</script>";
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