<?php
    function connectSettings() {
        require_once "{$_SERVER['DOCUMENT_ROOT']}/settings.php";
        
        print_r($connection);

        // foreach($tempSettings as $v) {
        //     $settings += $v;
        // }
        // return $settings;
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
    function createList() {
        $tasks = [];
        $login = $_SESSION['auth'];
        global $host, $mySqlUser, $mySqlPassword, $mysql_db;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);
        if($connect->connect_error) {
            echo "Error taskId:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            exit;
        }
        $query = "SELECT * FROM `{$login}ToDoList` WHERE `deletionStatus` <> 1";
        $result = $connect->query($query);
    
        if($result->num_rows === 0) {
            echo "Empty";
        } else {
            $todolist = $result->fetch_all(MYSQLI_ASSOC);
            $countTasks = 0;

            foreach($todolist as $v) {
                $taskId = 'task'.$v['taskId'];
                $tasks += array($taskId => array('taskId' => $v['taskId'], 'title' => $v['title'], 'doneStatus' => $v['doneStatus'], 'changedDateTime' => $v['changedDateTime'], 'deletionStatus' => $v['deletionStatus'], 'deletionDateTime' => $v['deletionDateTime']));
            }
            foreach ($tasks as $v):
                if($taskId['deletionStatus'] === 1) continue;
                if ($countTasks == 0):
                ?>
                  <div class="tasks-row">
                <?php
                    endif;
                ?>  
                    <div class="task-container">
                        <form class="update-form <?php if ($taskId['doneStatus'] == "1") echo "done"?>" action="/scripts/controllers/update.php" method="post">
                            <div class="task-item"> 
                                <input type="hidden" name="taskId" value="<?=$taskId?>">
                                <p> taskId: <?=$taskId?> </p>
                                <p> Title: <?=$taskId['title']?> </p>
                                <p> doneStatus: <?php 
                                    switch ($taskId['doneStatus']) {
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
                                <p> Date of change: <?php if(!empty($taskId['changedDateTime'])) echo $taskId['changedDateTime']; else echo "soon"?> </p>
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
        $connect->close();
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