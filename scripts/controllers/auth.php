<?php
    session_start();
    require_once '../../settings.php';
    function redirectMain() { 
        header('Location: http://'.HOST.'/index.php');
        exit;
    }
    function redirectAuthModel() { 
        header('Location: http://'.HOST.'/scripts/models/auth.php');
        exit;
    }
    function redirectLk() {
        header('Location: http://'.HOST.'/lk.php');
        exit;
    }
    function resetErrors() {
        unset($_SESSION['loginError']);
        unset($_SESSION['passwordError']);
        unset($_SESSION['authError']);
        unset($_SESSION['register']);
        unset ($_SESSION['newLoginError']);
        unset ($_SESSION['newPasswordError']);
        unset ($_SESSION['regError']);
        unset($_SESSION['createTableError']);
    }

    if (!isset($_SESSION['auth_step'])) {
        $_SESSION['auth_step'] = "first";
    }

    if ($_SESSION['auth_step'] === "first") {
        resetErrors();

        $salt = "123";
    
        $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
    
        if (strlen($login) === 0 || strlen($login) > 30) {
            $_SESSION['loginError'] = "Incorrect login";
            redirectMain(); 
        } elseif (strlen($password) < 4) {
            $_SESSION['passwordError'] = "Incorrect password";
            redirectMain();
        }
    
        $_SESSION['login'] = $login;
        $_SESSION['password'] = md5($password.$salt);
    
        redirectAuthModel();
    } elseif ($_SESSION['auth_step'] === "second") {
        unset($_SESSION['auth_step']);
        if(isset($_SESSION['authError'])) redirectMain();
        elseif(isset($_SESSION['auth'])) redirectLk();
    }