<?php
    session_start();
    require_once '../../settings.php';

    function redirectUpdateController() {
        $_SESSION['update_step'] = "second";
        header('Location: http://'.HOST.'/scripts/controllers/update.php');
    };
    function deleteTask($login, $task, $connect) {
        $deletionDateTime = date('Y-m-d H:i:s');
        $query = "UPDATE `{$login}ToDoList` SET `deletionStatus` = '1', `deletionDateTime` = '{$deletionDateTime}' WHERE `taskId` = {$task['taskId']}";
        $result = $connect->query($query);
        $connect->close();
        if(!$result) {
            $_SESSION['deleteError'] = 'The task not deleted';
        }
        redirectUpdateController();
    };
    function updateDone($login, $task, $connect) {
    if ($task['doneStatus'] == 1):
        $query = "UPDATE `{$login}ToDoList` SET `doneStatus` = '0', `changedDateTime` =  NULL WHERE `taskId` = {$task['taskId']}";
        $result = $connect->query($query);
        $connect->close();
        if(!$result) {
            $_SESSION['updateError'] = 'The task not updated';
        };
        elseif ($task['doneStatus'] == 0):
            $changedDateTime = date('Y-m-d H:i:s');
            $query = "UPDATE `{$login}ToDoList` SET `doneStatus` = '1', `changedDateTime` =  '{$changedDateTime}' WHERE `taskId` = {$task['taskId']}"; 
            $result = $connect->query($query);
            $connect->close();
            if(!$result) {
                $_SESSION['updateError'] = 'The task not updated';
            };
        endif;
        redirectUpdateController();
    }

    $login = $_SESSION['auth'];
    $action = $_SESSION['action'];
    $taskId = $_SESSION['taskId'];

    $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysqlDB);
    
    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        $_SESSION['actionError'] = "Connection error";
        redirectUpdateController();
    }
    
    $query = "SELECT * FROM `{$login}ToDoList` WHERE `taskId` = '$taskId'";
    $result = $connect->query($query);
    
    if($result->num_rows == 0) {
        $connect->close();
        $_SESSION['actionError'] = "Unknown task";
        redirectUpdateController();
    }
    
    $task = $result->fetch_array(MYSQLI_ASSOC);
    
    if ($action === "done") {
        updateDone($login, $task, $connect);
    } elseif ($action === "delete")
        deleteTask($login, $task, $connect);

    unset($_SESSION['action']);
    redirectUpdateController();