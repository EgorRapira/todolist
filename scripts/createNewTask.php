<?php
    session_start();
    require_once "{$_SERVER['DOCUMENT_ROOT']}/functions/functions.php";
    $connectionSettings = connectSettings();
    createNewTask();