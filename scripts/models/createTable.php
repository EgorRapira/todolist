<?php
    function createTable() {
        global $host, $mySqlUser, $mySqlPassword, $newLogin, $mysql_db;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);
        if($connect->connect_error) {
            echo "Error number:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            $_SESSION['regError'] = "Connection error";
            redirectRegisterController();
        }
        
        $query = "CREATE TABLE `{$newLogin}ToDoList` (
            `taskId` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `userId` int NOT NULL,
            `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
            `doneStatus` tinyint(1) NOT NULL DEFAULT '0',
            `changedDateTime` datetime DEFAULT NULL,
            `deletionStatus` tinyint(1) NOT NULL DEFAULT '0',
            `deletionDateTime` datetime DEFAULT NULL,
            FOREIGN KEY (`userId`)  REFERENCES `users` (`userId`) ON UPDATE CASCADE ON DELETE CASCADE
        )";

        $result = $connect->query($query);
        $connect->close();
        if(!$result) {
            $_SESSION['regError'] = "Table not created";
            redirectRegisterController();
        }
    }
