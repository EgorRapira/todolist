<?php
    session_start();
    require_once '../../settings.php';
    function resetErrors() {
        unset($_SESSION['actionError']);
        unset($_SESSION['updateError']);
        unset($_SESSION['deleteError']);
    }
    function redirectLk() {
        header('Location: http://'.HOST.'/lk.php');
        exit;
    }

    function redirectUpdateModel() { 
        header('Location: http://'.HOST.'/scripts/models/update.php');
        exit;
    }

    if (!isset($_SESSION['update_step'])) {
        $_SESSION['update_step'] = "first";
    }

    if ($_SESSION['update_step'] === "first") {
        resetErrors();

        if (!isset($_SESSION['auth'])) redirectLk();

        if (isset($_POST['taskId']) && $_POST['taskId'] != "") $taskId= $_POST['taskId']; else redirectLk();

        switch ($_POST['update']) {
            case "done":
                $action = "done";
                break;
            case "delete":
                $action = "delete";
                break;
            default: 
                $_SESSION['actionError'] = "Unknown action";
                redirectLk();
            }

        $_SESSION['action'] = $action;
        $_SESSION['taskId'] = $taskId;
        redirectUpdateModel();
    } elseif ($_SESSION['update_step'] === "second") {
        unset($_SESSION['update_step']);
        redirectLk();
    }