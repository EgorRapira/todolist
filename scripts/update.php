<?php
    session_start();
    require_once '../settings.php';

    function redirectBackError() {
        $_SESSION['actionError'] = "Unknown action";
        header('Location: ../lk.php');
        exit;
    }
    function RedirectBackSuccess() {
        header('Location: ../lk.php');
        exit;
    };

    switch ($_POST['update']) {
        case "done":
            $action = "done";
            break;
        case "delete":
            $action = "delete";
            break;
        default: 
            $_SESSION['actionError'] = "Unknown action";
            redirectBackError();
            break;
    }

    $login = $_SESSION['auth'];
    $_POST['id'] != ""? $taskID= $_POST['id']: redirectBackError();

    function done() {
        global $host, $mySqlUser, $mySqlPassword, $login, $taskID;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $login);

    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        exit;
    }

    $query = "SELECT * FROM `ToDoList` WHERE `id` = '$taskID'";
    $result = $connect->query($query);

    if($result->num_rows) {
        $task = $result->fetch_assoc();
    } else {
        redirectBackError();
    }

    if ($task['doneStatus'] == "0") {
        $query = "UPDATE `ToDoList` SET `doneStatus` = '1' WHERE `id` = '$taskID'";
        $result = $connect->query($query);
    } elseif ($task['doneStatus'] == "1") {
        $query = "UPDATE `ToDoList` SET `doneStatus` = '0' WHERE `id` = '$taskID'";
        $result = $connect->query($query);
    }

    RedirectBackSuccess();
    $connect->close();
    }
    
    function delete() {
        global $host, $mySqlUser, $mySqlPassword, $login, $taskID;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $login);

    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        exit;
    }

    $query = "SELECT * FROM `ToDoList` WHERE `id` = '$taskID'";
    $result = $connect->query($query);

    if($result->num_rows) {
        $query = "DELETE FROM `ToDoList` WHERE `id` = '$taskID'";
        $result = $connect->query($query);
    } else {
        redirectBackError();
    }

    RedirectBackSuccess();
    $connect->close();
    }

    if ($action == "done") {
        done();
    } elseif ($action == "delete") {
        delete();
    }
