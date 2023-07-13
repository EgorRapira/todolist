<?php
function connectSettings() {
    require_once "{$_SERVER['DOCUMENT_ROOT']}/settings.php";
    $tempKeys = array_keys($connection);
    $tempValues = array_values($connection);
    $connSettings = array_combine($tempKeys, $tempValues);
    return $connSettings;
}
function redirectToIndex() {
    header('HTTP/1.1 200 OK');
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
    exit;
}
function redirectToCabinet() {
    header('HTTP/1.1 200 OK');
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/cabinet.php');
    exit;
}
function resetRegisterAndAuthErrors() {
    unset($_SESSION['loginError']);
    unset($_SESSION['passwordError']);
    unset($_SESSION['authError']);
    unset($_SESSION['register']);
    unset($_SESSION['newLoginError']);
    unset($_SESSION['newPasswordError']);
    unset($_SESSION['regError']);
    unset($_SESSION['createTableError']);
}
function resetTaskCreationErrors() {
    unset($_SESSION['newTaskError']);
}
function resetUpdateTaskErrors() {
    unset($_SESSION['actionError']);
    unset($_SESSION['updateError']);
    unset($_SESSION['deleteError']);
}
function register() {
    global $connectionSettings;// получение подключения к базе данных
    resetRegisterAndAuthErrors();
    
    $newLogin = filter_var(trim($_POST['newLogin']), FILTER_SANITIZE_STRING);
    $newPassword = filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING);
    if (strlen($newLogin) === 0 || strlen($newLogin) > 30) {
        $_SESSION['newLoginError'] = "Incorrect login";
        redirectToIndex();
    } elseif (strlen($newPassword) < 4) {
        $_SESSION['newPasswordError'] = "Incorrect password";
        redirectToIndex();
    }  
    $newPassword = md5($newPassword.$connectionSettings['userSalt']);
    
    $connect = new mysqli($connectionSettings['host'], $connectionSettings['mySqlUser'], $connectionSettings['mySqlPassword'], $connectionSettings['mysqlDB']); // подключение к базе данных
    if($connect->connect_error) { // проверка ошибок при подключении
        $_SESSION['regError'] = "Connection error";
        redirectToIndex();
    }

    $query = "SELECT `login`,`password` FROM `users` WHERE `login` = '$newLogin'";
    $result = $connect->query($query);

    if($result->num_rows != 0) {
        $_SESSION['regError'] = "User already exist";
        $connect->close();
        redirectToIndex();
    }

    $query = "INSERT INTO `users` (`login`,`password`) VALUES ('$newLogin','$newPassword')";
    $result = $connect->query($query);
    if(!$result) {
        $_SESSION['regError'] = "User not created";
        $connect->close();
        redirectToIndex();
    }

    createTableForNewUser($connect, $newLogin);
    $_SESSION['register'] = true;
    redirectToIndex();
}
function createTableForNewUser($connect, $newLogin) {
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
        redirectToIndex();
    }
}
function auth() {
    global $connectionSettings;// получение подключения к базе данных
    resetRegisterAndAuthErrors(); // сброс ошибок

    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING); // обработка строки логин
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING); // обработка строки пароль

    if (strlen($login) === 0 || strlen($login) > 30) { // если длина логина = 0 или больше 30, то
        $_SESSION['loginError'] = "Incorrect login";
        redirectToIndex(); 
    } elseif (strlen($password) < 4) { // если длина пароля меньше 4, то
        $_SESSION['passwordError'] = "Incorrect password";
        redirectToIndex();
    }
    $password = md5($password.$connectionSettings['userSalt']); // хэширование пароля

    $connect = new mysqli($connectionSettings['host'], $connectionSettings['mySqlUser'], $connectionSettings['mySqlPassword'], $connectionSettings['mysqlDB']); // подключение к базе данных
    if($connect->connect_error) { // проверка ошибок при подключении
        $_SESSION['authError'] = "Connection error";
        redirectToIndex();
    }

    $query = "SELECT `login`,`password` FROM `users` WHERE `login` = '$login'"; // запрос на выборку юзера по логину
    $result = $connect->query($query);
    $connect->close();
    if ($result->num_rows === 0) { // если в ответе нет строк, то
        $_SESSION['authError'] = "User not found";
        redirectToIndex();
    } 
    $row = $result->fetch_assoc();
    if ($row['password'] !== $password) {
        $_SESSION['authError'] = "Incorrect password";
        redirectToIndex();
    }
    $_SESSION['auth'] = $login;
    redirectToCabinet();
}
function exitFromCabinet() {
    unset ($_SESSION['auth']);
    redirectToIndex();
}
function createToDoList() {
    global $connectionSettings;
    $tasks = [];
    $login = $_SESSION['auth'];
    $connect = new mysqli($connectionSettings['host'], $connectionSettings['mySqlUser'], $connectionSettings['mySqlPassword'], $connectionSettings['mysqlDB']); // подключение к базе данных
    if($connect->connect_error) {
        echo "Error taskId:".$connect->connect_errno.'<br>';
        echo "Error:".$connect->connect_error;
        exit;
    }
    $query = "SELECT * FROM `{$login}ToDoList` WHERE `deletionStatus` <> 1";
    $result = $connect->query($query);
    $connect->close();

    if($result->num_rows === 0) {
        echo "Empty";
    } else {
        $todolist = $result->fetch_all(MYSQLI_ASSOC);
        $countTasks = 0;
        foreach($todolist as $v) {
            $tasks[] = array('taskId' => $v['taskId'], 'title' => $v['title'], 'doneStatus' => $v['doneStatus'], 'changedDateTime' => $v['changedDateTime'], 'deletionStatus' => $v['deletionStatus'], 'deletionDateTime' => $v['deletionDateTime']);
        }
        foreach ($tasks as $v):
            if ($countTasks === 0):
            ?>
              <div class="tasks-row">
            <?php
                endif;
            ?>  
                <div class="task-container">
                    <form class="update-form <?php if ($v['doneStatus'] === "1") echo "done"?>" action="/scripts/updateTask.php" method="post">
                        <div class="task-item"> 
                            <input type="hidden" name="taskId" value="<?=$v['taskId']?>">
                            <p> taskId: <?=$v['taskId']?> </p>
                            <p> Title: <?=$v['title']?> </p>
                            <p> doneStatus: <?php 
                                switch ($v['doneStatus']) {
                                    case "1": 
                                        echo "done";
                                        break;
                                    case "0":
                                        echo "in work";
                                        break;
                                    default: 
                                        exit;
                                }?> 
                            </p>
                            <div class="update-form-buttons">
                                <button class="update form-button" id="update" name="update" value="done"> Done </button>
                                <button class="delete form-button" id="delete" name="update" value="delete"> Delete </button>
                            </div>
                            <p> Date of change: <?php if(!empty($v['changedDateTime'])) echo $v['changedDateTime']; else echo "soon"?> </p>
                        </div>
                    </form>
                </div>
            <?php
            $countTasks ++;
            if ($countTasks == 2):
                $countTasks = 0;
            ?>
                </div>
            <?php
                endif;
        endforeach;
    }    
}
function createNewTask() {
    global $connectionSettings;
    resetTaskCreationErrors();
    $login = $_SESSION['auth'];
    $newTaskTitle = trim($_POST['newTaskTitle']);
    if (strlen($newTaskTitle) === 0 || strlen($newTaskTitle) > 50) {
        $_SESSION['newTaskError'] = "Incorrect length for new task";
        redirectToCabinet(); 
    }
    // Create a new to do list item and add it into the database
    $connect = new mysqli($connectionSettings['host'], $connectionSettings['mySqlUser'], $connectionSettings['mySqlPassword'], $connectionSettings['mysqlDB']); // подключение к базе данных
    if($connect->connect_error) {
        $_SESSION['newTaskError'] = "Connection error";
        redirectToCabinet();
    }
    $query = "SELECT `userId` FROM `users` WHERE `login` = '$login'";
    $result = $connect->query($query);
    if(!$result) {
        $connect->close();
        $_SESSION['newTaskError'] = 'User not found';
        redirectToCabinet();
    }
    $userId = $result->fetch_assoc()['userId'];

    $query = "INSERT INTO `{$login}ToDoList` (`userId`,`title`) VALUES ('$userId', '$newTaskTitle')";
    $result = $connect->query($query);
    $connect->close();
    if(!$result) {
        $_SESSION['newTaskError'] = 'New task not created';
    }
    redirectToCabinet();
}
function updateTask() {
    global $connectionSettings;
    resetUpdateTaskErrors();

    if (!isset($_SESSION['auth'])) redirectToCabinet(); else $login = $_SESSION['auth'];
    if (isset($_POST['taskId']) && $_POST['taskId'] != "") $taskId= $_POST['taskId']; else redirectToCabinet();

    $connect = new mysqli($connectionSettings['host'], $connectionSettings['mySqlUser'], $connectionSettings['mySqlPassword'], $connectionSettings['mysqlDB']); // подключение к базе данных
    if($connect->connect_error) { // проверка ошибок при подключении
        $_SESSION['actionError'] = "Connection error";
        redirectToCabinet();
    }
    
    $query = "SELECT * FROM `{$login}ToDoList` WHERE `taskId` = '$taskId'";
    $result = $connect->query($query);
    
    if($result->num_rows === 0) {
        $connect->close();
        $_SESSION['actionError'] = "Unknown task";
        redirectToCabinet();
    }

    $task = $result->fetch_array(MYSQLI_ASSOC);
    
    switch ($_POST['update']) {
        case "done":
            updateDoneStatus($login, $task, $connect);
            break;
        case "delete":
            deleteTask($login, $task, $connect);
            break;
        default: 
            $_SESSION['actionError'] = "Unknown action";
            redirectToCabinet();
    }
}
function updateDoneStatus($login, $task, $connect) {
    if ($task['doneStatus'] == 1):
        $query = "UPDATE `{$login}ToDoList` SET `doneStatus` = '0', `changedDateTime` =  NULL WHERE `taskId` = {$task['taskId']}";
        $result = $connect->query($query);
        $connect->close();
        if(!$result) {
            $_SESSION['updateError'] = 'The task not updated';
        };
        elseif ($task['doneStatus'] == 0):
            $changedDateTime = date('Y-m-d H:i:s');
            $query = "UPDATE `{$login}ToDoList` SET `doneStatus` = '1', `changedDateTime` =  '{$changedDateTime}' WHERE `taskId` = {$task['taskId']}"; 
            $result = $connect->query($query);
            $connect->close();
            if(!$result) {
                $_SESSION['updateError'] = 'The task not updated';
            };
        endif;
        redirectToCabinet();
}
function deleteTask($login, $task, $connect) {
    $deletionDateTime = date('Y-m-d H:i:s');
    $query = "UPDATE `{$login}ToDoList` SET `deletionStatus` = '1', `deletionDateTime` = '{$deletionDateTime}' WHERE `taskId` = {$task['taskId']}";
    $result = $connect->query($query);
    $connect->close();
    if(!$result) {
        $_SESSION['deleteError'] = 'The task not deleted';
    }
    redirectToCabinet();
};