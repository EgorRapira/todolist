<?php
    function createDataBase() {
        global $host, $mySqlUser, $mySqlPassword, $login;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword);
        if($connect->connect_error) {
            echo "Error number:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            exit;
        }
    
        $query = "CREATE DATABASE $login DEFAULT CHARACTER SET UTF8MB4 DEFAULT COLLATE utf8mb4_general_ci";
        $result = $connect->query($query);

        if($result != true) {
            $_SESSION['unexpectedError'] = "Database not created";
            redirectError();
        }
    
        $query = "USE $login";
        $result = $connect->query($query);
        
        $query = "CREATE TABLE `ToDoList` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(50) NOT NULL,
            `doneStatus` BOOLEAN NOT NULL DEFAULT false
        )";

        $result = $connect->query($query);

        if($result != true) {
            $_SESSION['unexpectedError'] = "Table not created";
            redirectError();
        }
    
        $connect->close();
    }
