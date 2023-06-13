<?php

    class Task {
        public int $number;
        public string $title;
        public string $doneStatus;
        public function __construct(int $number, string $title, string $doneStatus) {
            $this->number = $number;
            $this->title = $title;
            $this->doneStatus = $doneStatus;
        }
    }

    $tasks = [];

    if (isset($_SESSION['auth'])) {
        $login = $_SESSION['auth'];
    } else {
        redirectError();
    }

    function createList() {
        global $host, $mySqlUser, $mySqlPassword, $login, $tasks;
        $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $login);
        if($connect->connect_error) {
            echo "Error number:".$connect->connect_errno.'<br>';
            echo "Error:".$connect->connect_error;
            exit;
        }
        $query = "SELECT * FROM `ToDoList`";
        $result = $connect->query($query);
    
        if($result->num_rows == 0) {
            echo "Nothing";
        } else {
            $todolist = $result->fetch_all(MYSQLI_ASSOC);
            $countTasks = 0;

            foreach($todolist as $v) {
                $taskId = 'task'.$v['id'];
                $tasks += array($taskId => ${'task'.$v['id']} = new Task($v['id'], $v['title'], $v['doneStatus']));
            }
            foreach ($tasks as $v):
                if ($countTasks == 0):
                ?>
                  <div class="tasks-row">
                <?php
                    endif;
                ?>  
                    <div class="task-container">
                        <form class="update-form <?php if ($v->doneStatus == "1") echo "done"?>" action="/scripts/update.php" method="post">
                            <div class="task-item"> 
                                <input type="hidden" name="id" value="<?=$v->number?>">
                                <p> Number: <?=$v->number?> </p>
                                <p> Title: <?=$v->title?> </p>
                                <p> doneStatus: <?php if ($v->doneStatus == "1") echo "done"; elseif ($v->doneStatus == "0") echo "in work" ?> </p>
                                <div class="update-form-buttons">
                                    <button class="update form-button" id="update" name="update" value="done"> Done </button>
                                    <button class="delete form-button" id="delete" name="update" value="delete"> Delete </button>
                                </div>
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
