<?php
    session_start();
    require_once '../settings.php';
    function redirectBack() { 
        header('Location: ../index.php');
        exit;
    }

    function redirectLk() {
        header('Location: ../lk.php');
        exit;
    }
    function resetErrors() {
        unset($_SESSION['loginError']);
        unset($_SESSION['passwordError']);
        unset($_SESSION['authError']);
        unset($_SESSION['register']);
        unset ($_SESSION['newLoginError']);
        unset ($_SESSION['newPasswordError']);
        unset ($_SESSION['regError']);
        unset($_SESSION['createTableError']);
    }

    resetErrors();

    $salt = "123";

    $login = filter_var(trim($_POST['login']));
    $password = filter_var(trim($_POST['password']));

    if (strlen($login) === 0 || strlen($login) > 30) {
        $_SESSION['loginError'] = "Incorrect login";
        redirectBack(); 
    } elseif (strlen($password) < 4) {
        $_SESSION['passwordError'] = "Incorrect password";
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
    $connect->close();
    if($result->num_rows == 0) {
        $_SESSION['authError'] = "User wasn't find";
    } else {
        $row = $result->fetch_assoc();
        if ($row['password'] == $password) {
            $_SESSION['auth'] = $login;
            redirectLk();
        }
        else {
            $_SESSION['authError'] = "Incorrect password";
        }
    }
    redirectBack();

    