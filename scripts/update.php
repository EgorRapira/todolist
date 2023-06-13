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

    function update($action) {
    global $host, $mySqlUser, $mySqlPassword, $login, $taskID;
    $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $login);

    if($connect->connect_error) {
        echo "Error number:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        exit;
    }

    $query = "SELECT * FROM `ToDoList` WHERE `id` = '$taskID'";
    $result = $connect->query($query);

    if($result->num_rows < 0) {
        redirectBackError();
    }

    if ($action == "done") {
        $query = "CALL `update_doneStatus`($taskID)";
        $result = $connect->query($query);
        if(!$result) {
            redirectBackError();
        }
    
        RedirectBackSuccess();
        $connect->close();
    } elseif ($action == "delete")

    $query = "CALL `delete_task`($taskID)";
    $result = $connect->query($query);
    if(!$result) {
        redirectBackError();
    }

    RedirectBackSuccess();
    $connect->close();

    }

    update($action);