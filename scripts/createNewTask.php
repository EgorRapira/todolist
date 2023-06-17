<?php
    session_start();
    unset($_SESSION['newTaskError']);
    require_once '../settings.php';

    function redirectBack() {
        header('Location: ../lk.php');
        exit;
    }

    $login = $_SESSION['auth'];
    $task = ["title" => $_POST['newTaskTitle']];

    function createNewTask() {  
        global $host, $mySqlUser, $mySqlPassword, $mysql_db, $login, $task;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);
        if($connect->connect_error) {
            echo "Error number:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            redirectBack();
        }
        $query = "INSERT INTO `{$login}ToDoList` (`title`) VALUES ('" . $task['title'] . "')";
        $result = $connect->query($query);
        if(!$result) {
            $_SESSION['newTaskError'] = 'New task not created';
        }
        $connect->close();
        redirectBack();
    }

    createNewTask();