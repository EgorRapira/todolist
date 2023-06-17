<?php
    function createTable() {
        global $host, $mySqlUser, $mySqlPassword, $login, $mysql_db;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);
        if($connect->connect_error) {
            echo "Error number:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            redirectBack();
        }
        
        $query = "CREATE TABLE `{$login}ToDoList` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `title` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
            `doneStatus` tinyint(1) NOT NULL DEFAULT '0',
            `changedDateTime` datetime DEFAULT NULL,
            `deletionStatus` tinyint(1) NOT NULL DEFAULT '0',
            `deletionDateTime` datetime DEFAULT NULL
        )";

        $result = $connect->query($query);
        $connect->close();
        if($result != true) {
            $_SESSION['createTableError'] = "Table not created";
            redirectBack();
        }
    }
