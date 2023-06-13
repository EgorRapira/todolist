<?php
    session_start();
    require_once '../settings.php';
    function redirectError() {
        header('Location: ../index.php');
        exit;
    }

    function resetErrors() {
        unset($_SESSION['loginError']);
        unset($_SESSION['passwordError']);
        unset($_SESSION['authError']);
        unset($_SESSION['register']);
    }

    function redirectSuccess($login) {
        resetErrors();
        $_SESSION['auth'] = $login;
        header('Location: ../lk.php');
        exit;
    }

    $salt = "123";

    $login = filter_var(trim($_POST['login']));
    $password = filter_var(trim($_POST['password']));

    if (strlen($login) === 0 || strlen($login) > 30) {
        $_SESSION['loginError'] = "Incorrect login";
        redirectError();
    } elseif (strlen($password) < 4) {
        $_SESSION['passwordError'] = "Incorrect password";
        redirectError();
    }

    $password = md5($password.$salt);

    $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);

    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        exit;
    }

    $query = "SELECT `login`,`password` FROM `users` WHERE `login` = '$login'";
    $result = $connect->query($query);

    if($result->num_rows == 0) {
        $_SESSION['authError'] = "User wasn't find";
        redirectError();
    } else {
        $row = $result->fetch_assoc();
        if ($row['password'] == $password) {
            redirectSuccess($login);
        }
        else {
            $_SESSION['authError'] = "Incorrect password";
            redirectError();
        }
    }

    $connect->close();

    