<?php
    session_start();
    require_once "{$_SERVER['DOCUMENT_ROOT']}/functions/functions.php";
    connectSettings();

    function redirectRegisterModel() { 
        header('Location: http://'.HOST.'/scripts/models/register.php');
        exit;
    }

    if (!isset($_SESSION['register_step'])) {
        $_SESSION['register_step'] = "first";
    }

    if ($_SESSION['register_step'] === "first") {
        resetRegisterAndAuthErrors();

        $userSalt = "123";

        $newLogin = filter_var(trim($_POST['newLogin']), FILTER_SANITIZE_STRING);
        $newPassword = filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING);

        if (strlen($newLogin) === 0 || strlen($newLogin) > 30) {
            $_SESSION['newLoginError'] = "Incorrect login";
            redirectToIndex();
        } elseif (strlen($newPassword) < 4) {
            $_SESSION['newPasswordError'] = "Incorrect password";
            redirectToIndex();
        }
        $_SESSION['newLogin'] = $newLogin;  
        $_SESSION['newPassword'] = md5($newPassword.$userSalt);
        redirectRegisterModel();
    } elseif ($_SESSION['register_step'] === "second") {
        unset($_SESSION['register_step']);
        redirectToIndex();
    }
    