<?php

//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: *");
//header("Access-Control-Allow-Headers: *");
//header('Content-type: application/json');

require_once '../model/User.php';
require_once '../dao/UserDAO.php';

class UserController
{

    const USER_ACTION_SUCCESS = 0;
    const USER_ACTION_FAIL_DUE_USERNAME_DUB = 1;
    const USER_ACTION_FAIL_DUE_PASSWORDS_MISMATCH = 2;

    private $dao;

    public function __construct()
    {
        $this->dao = new UserDAO();
    }


    function handleRequests()
    {
        if (isset($_POST['authenticate']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->dao->authenticateUser($_POST['username'], $_POST['password']);
        }

        if (isset($_POST['save']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password_check = $_POST['password-check'];

            $user = new User(-1, $name, $username, $password);
            $result = $this->dao->getUserByUsername($username);

            if (empty($result)) {
                if ($password === $password_check) {
                    $this->dao->insert($user);
                    return self::USER_ACTION_SUCCESS;
                } else {
                    return self::USER_ACTION_FAIL_DUE_PASSWORDS_MISMATCH;
                }
            } else {
                return self::USER_ACTION_FAIL_DUE_USERNAME_DUB;
            }
        }

        if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUser = $_SESSION['loggedUser'];

            $isUnique = true;
            $allUsers = $this->dao->findAll();

            if (!empty($allUsers)) {
                foreach ($allUsers as $user) {
                    if ($_POST['username'] === $user->getUsername()
                        && $currentUser->getUserId() !== $user->getUserId())
                        $isUnique = false;
                }
            }

            if ($isUnique && ($_POST['username'] !== null)) {
                if ($_POST['password'] === $_POST['password-check']) {

                    $updatedUser = new User($currentUser->getUserId(),
                        $_POST['name'],
                        $_POST['username'],
                        $_POST['password']);
                    $this->dao->update($updatedUser);
                    $_SESSION['loggedUser'] = $updatedUser;
                    return self::USER_ACTION_SUCCESS;
                } else {
                    return self::USER_ACTION_FAIL_DUE_PASSWORDS_MISMATCH;
                }
            } else {
                return self::USER_ACTION_FAIL_DUE_USERNAME_DUB;
            }
        }
    }

}