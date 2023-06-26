<?php
    require_once "{$_SERVER['DOCUMENT_ROOT']}/templates/page_template_header.php";
    if(!isset($_SESSION['auth'])) redirectToIndex();
?>

<header class="header">
    <form class="exit-form" action="/scripts/controllers/exit.php" method="post">
        <label for="exit" class="label-exit"> Pass for exit </label>
        <button class="form-button" id="exit" name="exit"> Exit </button>
    </form>
</header>

<main class="main">
    <h1> Hello, <?= $_SESSION['auth'] ?> </h1>
    <div class="new-task-container">
        <form class="create-form" action="/scripts/controllers/createNewTask.php" method="post">
            <div class="new-task-item"> 
                <label for="newTaskTitle"> Create new task: </label>
                <input type="text" name="newTaskTitle" id="newTaskTitle" placeholder="Task title">
                <button class="create form-button" id="create" name="create" value="create"> Create </button>
            </div>
        </form>
    </div>
    <h2> Your task list: </h2>
    <?php
        createList();
        if (isset($_SESSION['actionError'])):
            ?>
                <div class="error"> <?=$_SESSION['actionError'];?> </div>
            <?php
        endif;
        if (isset($_SESSION['newTaskError'])):
            ?>
                <div class="error"> <?=$_SESSION['newTaskError'];?> </div>
            <?php
        endif;
        if (isset($_SESSION['updateError'])):
            ?>
                <div class="error"> <?=$_SESSION['updateError'];?> </div>
            <?php
        endif;
    ?>
</main>
<?php
    require_once '/templates/page_template_footer.php';
?>