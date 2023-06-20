<?php 
    session_start();
    require_once '../../settings.php';
    require_once 'createTable.php';
    function redirectRegisterController() {
        header('Location: http://'.HOST.'/scripts/controllers/register.php');
    };

    $_SESSION['register_step'] = "second";

    $newLogin = $_SESSION['newLogin'];
    $newPassword = $_SESSION['newPassword'];
    $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);

    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        $_SESSION['regError'] = "Connection error";
        redirectRegisterController();
    }

    $query = "SELECT `login`,`password` FROM `users` WHERE `login` = '$newLogin'";
    $result = $connect->query($query);

    if($result->num_rows != 0) {
        $_SESSION['regError'] = "User already exist";
        $connect->close();
        redirectRegisterController();
    }

    $query = "INSERT INTO `users` (`login`,`password`) VALUES ('$newLogin','$newPassword')";
    $result = $connect->query($query);
    $connect->close();
    if(!$result) {
        $_SESSION['regError'] = "User not created";
        redirectRegisterController();
    }

    createTable();

    unset($_SESSION['newLogin']);
    unset($_SESSION['newPassword']);
    $_SESSION['register'] = $newLogin;
    redirectRegisterController();