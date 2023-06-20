<?php
    session_start();
    unset($_SESSION['newTaskError']);
    require_once '../../settings.php';

    function redirectCreateNewTaskModel() { 
        header('Location: http://'.HOST.'/scripts/models/createNewTask.php');
        exit;
    }

    function redirectLk() {
        header('Location: http://'.HOST.'/lk.php');
        exit;
    }

    $newTaskTitle = trim($_POST['newTaskTitle']);

    function resetErrors() {
        unset($_SESSION['newTaskError']);
    }

    if (!isset($_SESSION['createNewTask_step'])) {
        $_SESSION['createNewTask_step'] = "first";
    }

    if ($_SESSION['createNewTask_step'] === "first") {
        resetErrors();
        if (strlen($newTaskTitle) === 0 || strlen($newTaskTitle) > 50) {
            $_SESSION['newTaskError'] = "Incorrect length for new task";
            redirectLk(); 
        }
        $_SESSION['newTaskTitle'] = $newTaskTitle;
        redirectCreateNewTaskModel();

    } elseif ($_SESSION['createNewTask_step'] === "second") {
        unset($_SESSION['createNewTask_step']);
        redirectLk();
    };
