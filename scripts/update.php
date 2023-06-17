<?php
    session_start();
    function resetErrors() {
        unset($_SESSION['actionError']);
        unset($_SESSION['updateError']);
        unset($_SESSION['deleteError']);
    }

    require_once '../settings.php';

    function redirectBack() {
        header('Location: ../lk.php');
        exit;
    };

    function deleteTask($login, $task, $connect) {
        $deletionDateTime = date('Y-m-d H:i:s');
        $query = "UPDATE `{$login}ToDoList` SET `deletionStatus` = '1', `deletionDateTime` = '{$deletionDateTime}' WHERE `id` = {$task['id']}";
        $result = $connect->query($query);
        $connect->close();
        if(!$result) {
            $_SESSION['deleteError'] = 'The task not deleted';
        }
    redirectBack();
    };

    function updateDone($login, $task, $connect) {
    if ($task['doneStatus'] == 1):
        $query = "UPDATE `{$login}ToDoList` SET `doneStatus` = '0', `changedDateTime` =  NULL WHERE `id` = {$task['id']}";
        $result = $connect->query($query);
        $connect->close();
        if(!$result) {
            $_SESSION['updateError'] = 'The task not updated';
        };
    elseif ($task['doneStatus'] == 0):
        $changedDateTime = date('Y-m-d H:i:s');
        $query = "UPDATE `{$login}ToDoList` SET `doneStatus` = '1', `changedDateTime` =  '{$changedDateTime}' WHERE `id` = {$task['id']}"; 
        $result = $connect->query($query);
        $connect->close();
        if(!$result) {
            $_SESSION['updateError'] = 'The task not updated';
        };
    endif;
    redirectBack();
    }

    resetErrors();

    switch ($_POST['update']) {
        case "done":
            $action = "done";
            break;
        case "delete":
            $action = "delete";
            break;
        default: 
            $_SESSION['actionError'] = "Unknown action";
            redirectBack();
    }

    $login = $_SESSION['auth'];
    $_POST['id'] != ""? $taskId= $_POST['id']: redirectBack();

    function update($action) {
    global $host, $mySqlUser, $mySqlPassword, $mysql_db, $login, $taskId;
    $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);

    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        $connect->close();
        exit;
    }

    $query = "SELECT * FROM `{$login}ToDoList` WHERE `id` = '$taskId'";
    $result = $connect->query($query);

    if($result->num_rows <= 0) {
        $connect->close();
        redirectBack();
    }

    $task = $result->fetch_array(MYSQLI_ASSOC);

    if ($action === "done") {
        updateDone($login, $task, $connect);
    } elseif ($action === "delete")
        deleteTask($login, $task, $connect);
    }

    update($action);