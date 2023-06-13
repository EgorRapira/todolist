<?php
    session_start();
    require_once '../settings.php';
    function redirectError() {
        header('Location: ../index.php');
        exit;
    }
    function resetErrors() {
        unset ($_SESSION['newLoginError']);
        unset ($_SESSION['newPasswordError']);
        unset ($_SESSION['regError']);
    }

    function redirectSuccess($login) {
        $_SESSION['register'] = $login;
        header('Location: ../index.php');
        exit;
    }

    $salt = "123";

    resetErrors();

    $login = filter_var(trim($_POST['login']));
    $password = filter_var(trim($_POST['password']));

    if (strlen($login) === 0 || strlen($login) > 30) {
        $_SESSION['newLoginError'] = "Incorrect login";
        redirectError();
    } elseif (strlen($password) < 4) {
        $_SESSION['newPasswordError'] = "Incorrect password";
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

    if($result->num_rows != 0) {
        $_SESSION['regError'] = "User already exist";
        redirectError();
    }

    $query = "INSERT INTO `users` (`login`,`password`) VALUES ('$login','$password')";
    $result = $connect->query($query);

    require_once 'createDataBase.php';
    createDataBase();

    redirectSuccess($login);

    $connect->close();

    