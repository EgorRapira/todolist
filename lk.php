<?php
    session_start();
    $cssUrl = 'css/lk.css';
    require_once 'scripts/blocks/header.php';
    require_once 'settings.php';
    function redirectError() {
        header('Location: index.php');
        exit;
    }
?>

<header class="header">
    <form class="exit-form" action="/scripts/exit.php" method="post">
        <label for="exit" class="label-exit"> Pass for exit </label>
        <button class="form-button" id="exit" name="exit"> Exit </button>
    </form>
</header>

<main class="main">
    <?php
        if(isset($_SESSION['auth'])):
    ?>
        <h1> Hello, <?= $_SESSION['auth'] ?> </h1>
        <div class="new-task-container">
            <form class="create-form" action="/scripts/createNewTask.php" method="post">
                <div class="new-task-item"> 
                    <label for="newTaskTitle"> Create new task: </label>
                    <input type="text" name="newTaskTitle" id="newTaskTitle" placeholder="Task title">
                    <button class="create form-button" id="create" name="create" value="create"> Create </button>
                </div>
            </form>
        </div>
        <h2> Your task list: </h2>
    <?php
        else:
            redirectError();
        endif;
        require_once 'scripts/ToDoList.php';
    ?>
</main>

<?php
    require_once 'scripts/blocks/footer.php';
?>