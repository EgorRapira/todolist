<?php
    class Task {
        public int $taskId;
        public string $title;
        public bool $doneStatus;
        public $changedDateTime; 
        public bool $deletionStatus;
        public $deletionDateTime;
        public function __construct(int $taskId, string $title, bool $doneStatus, $changedDateTime, bool $deletionStatus, $deletionDateTime) {
            $this->taskId = $taskId;
            $this->title = $title;
            $this->doneStatus = $doneStatus;
            $this->changedDateTime = $changedDateTime;
            $this->deletionStatus = $deletionStatus;
            $this->deletionDateTime = $deletionDateTime;
        }
    }

    $tasks = [];
    $login = $_SESSION['auth'];

    function createList() {
        global $host, $mySqlUser, $mySqlPassword, $mysql_db, $login, $tasks;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysql_db);
        if($connect->connect_error) {
            echo "Error taskId:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            exit;
        }
        $query = "SELECT * FROM `{$login}ToDoList` WHERE `deletionStatus` <> 1";
        $result = $connect->query($query);
    
        if($result->num_rows == 0) {
            echo "Empty";
        } else {
            $todolist = $result->fetch_all(MYSQLI_ASSOC);
            $countTasks = 0;

            foreach($todolist as $v) {
                $taskId = 'task'.$v['taskId'];
                $tasks += array($taskId => ${'task'.$v['taskId']} = new Task($v['taskId'], $v['title'], $v['doneStatus'], $v['changedDateTime'], $v['deletionStatus'], $v['deletionDateTime']));
            }
            foreach ($tasks as $v):
                if($v->deletionStatus == 1) continue;
                if ($countTasks == 0):
                ?>
                  <div class="tasks-row">
                <?php
                    endif;
                ?>  
                    <div class="task-container">
                        <form class="update-form <?php if ($v->doneStatus == "1") echo "done"?>" action="/scripts/controllers/update.php" method="post">
                            <div class="task-item"> 
                                <input type="hidden" name="taskId" value="<?=$v->taskId?>">
                                <p> taskId: <?=$v->taskId?> </p>
                                <p> Title: <?=$v->title?> </p>
                                <p> doneStatus: <?php if ($v->doneStatus == "1") echo "done"; elseif ($v->doneStatus == "0") echo "in work" ?> </p>
                                <div class="update-form-buttons">
                                    <button class="update form-button" id="update" name="update" value="done"> Done </button>
                                    <button class="delete form-button" id="delete" name="update" value="delete"> Delete </button>
                                </div>
                                <p> Date of change: <?php if(!empty($v->changedDateTime)) echo $v->changedDateTime; else echo "soon"?> </p>
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

    createList();