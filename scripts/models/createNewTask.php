<?php
    session_start();
    require_once '../../settings.php';

    function redirectCreateNewTaskController() {
        $_SESSION['createNewTask_step'] = "second";
        header('Location: http://'.HOST.'/scripts/controllers/createNewTask.php');
        exit;
    }
    // Create a new to do list item and add it into the database
        $login = $_SESSION['auth'];
        $newTaskTitle = $_SESSION['newTaskTitle'];
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);
        if($connect->connect_error) {
            echo "Error number:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            $_SESSION['newTaskError'] = "Connection error";
            redirectCreateNewTaskController();
        }
        
        $query = "SELECT `userId` FROM `users` WHERE `login` = '$login'";
        $result = $connect->query($query);
        if(!$result) {
            $_SESSION['newTaskError'] = 'User not found';
        }
        $userId = $result->fetch_assoc()['userId'];

        $query = "INSERT INTO `{$login}ToDoList` (`userId`,`title`) VALUES ('$userId', '$newTaskTitle')";
        $result = $connect->query($query);
        if(!$result) {
            $_SESSION['newTaskError'] = 'New task not created';
        }
        $connect->close();
        unset($_SESSION['newTaskTitle']);
        redirectCreateNewTaskController();