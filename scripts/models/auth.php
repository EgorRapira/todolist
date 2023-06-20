<?php
    session_start();
    require_once '../../settings.php';

    function redirectAuthController() {
        $_SESSION['auth_step'] = "second";
        header('Location: http://'.HOST.'/scripts/controllers/auth.php');
    };

    $login = $_SESSION['login'];
    $password = $_SESSION['password'];

    $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);

    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        $_SESSION['authError'] = "Connection error";
        redirectAuthController();
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
        }
        else {
            $_SESSION['authError'] = "Incorrect password";
        }
    }
    unset($_SESSION['login']);
    unset($_SESSION['password']);
    redirectAuthController();