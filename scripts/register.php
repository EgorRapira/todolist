<?php
    session_start();
    require_once '../settings.php';
    function resetErrors() {
        unset ($_SESSION['newLoginError']);
        unset ($_SESSION['newPasswordError']);
        unset ($_SESSION['regError']);
        unset($_SESSION['createTableError']);
        unset($_SESSION['loginError']);
        unset($_SESSION['passwordError']);
        unset($_SESSION['authError']);
        unset($_SESSION['register']);
    }
    function redirectBack() {
        header('Location: ../index.php');
        exit;
    }

    resetErrors();

    $salt = "123";

    $login = filter_var(trim($_POST['login']));
    $password = filter_var(trim($_POST['password']));

    if (strlen($login) === 0 || strlen($login) > 30) {
        $_SESSION['newLoginError'] = "Incorrect login";
        redirectBack();
    } elseif (strlen($password) < 4) {
        $_SESSION['newPasswordError'] = "Incorrect password";
        redirectBack();
    }

    $password = md5($password.$salt);

    $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);

    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        redirectBack();
    }

    $query = "SELECT `login`,`password` FROM `users` WHERE `login` = '$login'";
    $result = $connect->query($query);

    if($result->num_rows != 0) {
        $_SESSION['regError'] = "User already exist";
        $connect->close();
        redirectBack();
    }

    $query = "INSERT INTO `users` (`login`,`password`) VALUES ('$login','$password')";
    $result = $connect->query($query);
    $connect->close();
    if(!$result) {
        $_SESSION['regError'] = "User not created";
        redirectBack();
    }

    require_once 'createTable.php';
    createTable();

    $_SESSION['register'] = $login;
    redirectBack();