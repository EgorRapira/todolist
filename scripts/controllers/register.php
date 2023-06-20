<?php
    session_start();
    require_once '../../settings.php';
    function resetErrors() {
        unset ($_SESSION['newLoginError']);
        unset ($_SESSION['newPasswordError']);
        unset ($_SESSION['regError']);
        unset($_SESSION['createTableError']);
        unset($_SESSION['loginError']);
        unset($_SESSION['passwordError']);
        unset($_SESSION['authError']);
        unset($_SESSION['register']);
    }
    function redirectMain() { 
        header('Location: http://'.HOST.'/index.php');
        exit;
    }

    function redirectRegisterModel() { 
        header('Location: http://'.HOST.'/scripts/models/register.php');
        exit;
    }

    if (!isset($_SESSION['register_step'])) {
        $_SESSION['register_step'] = "first";
    }

    if ($_SESSION['register_step'] === "first") {
        resetErrors();

        $salt = "123";

        $newLogin = filter_var(trim($_POST['newLogin']), FILTER_SANITIZE_STRING);
        $newPassword = filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING);

        if (strlen($newLogin) === 0 || strlen($newLogin) > 30) {
            $_SESSION['newLoginError'] = "Incorrect login";
            redirectMain();
        } elseif (strlen($newPassword) < 4) {
            $_SESSION['newPasswordError'] = "Incorrect password";
            redirectMain();
        }
        $_SESSION['newLogin'] = $newLogin;  
        $_SESSION['newPassword'] = md5($newPassword.$salt);
        redirectRegisterModel();
    } elseif ($_SESSION['register_step'] === "second") {
        unset($_SESSION['register_step']);
        redirectMain();
    }
    