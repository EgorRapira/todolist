<?php
    session_start();
    require_once '../../settings.php';
    unset ($_SESSION['auth']);
    header('Location: http://'.HOST.'/index.php');