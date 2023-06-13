<?php
    session_start();
    require_once '../settings.php';

    function redirectError() {
        header('Location: ../index.php');
        exit;
    }

    function redirectSuccess() {
        header('Location: ../lk.php');
        exit;
    }

    if (isset($_SESSION['auth'])) {
        $login = $_SESSION['auth'];
    } else {
        // redirectError();
    }

    $task = ["title" => $_POST['newTaskTitle']];

    print_r($task);

    function createNewTask() {
        global $host, $mySqlUser, $mySqlPassword, $login, $task;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $login);
        if($connect->connect_error) {
            echo "Error number:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            exit;
        }
        $query = "INSERT INTO `ToDoList` (`title`) VALUES ('" . $task['title'] . "')";
        $result = $connect->query($query);
    
        $connect->close();
        redirectSuccess();
    }

    createNewTask();
