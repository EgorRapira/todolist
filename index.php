<?php
    session_start();
    $cssUrl = 'css/style.css';
    require_once 'scripts/blocks/header.php';
?>

<main class="main">
    <form class="form-group" action="scripts/auth.php" method="post" accept-charset="UTF-8" novalidate>
        <h2> Authorization </h2>
        <div class="form-item">
            <label class="form-item-label" for="login">Your login</label>
            <input class="form-item-input" type="text" id="login" name="login" value="">
            <?php if(isset($_SESSION['loginError'])):
            ?>
            <div class="error">
                <?= $_SESSION['loginError']?>
            </div>
            <?php
            endif?>
        </div>
        <div class="form-item">
            <label class="form-item-label" for="password">Your password</label>
            <input class="form-item-input" type="password" id="password" name="password" value="">
            <?php if(isset($_SESSION['passwordError'])):
            ?>
            <div class="error">
                <?= $_SESSION['passwordError']?>
            </div>
            <?php
            endif?>
        </div>
        <div class="form-item">
            <input class="form-item-button" type="submit" value="Authorization">
        </div>
        <?php if(isset($_SESSION['authError'])):
            ?>
            <div class="error">
                <?= $_SESSION['authError']?>
            </div>
            <?php
            endif?>
    </form>
    <?php 
        if(isset($_SESSION['register'])):
    ?>
        <h3> User was created, please go authorization. </h3>
    <?php 
        else:
    ?>
    <form class="form-group" action="scripts/register.php" method="post" accept-charset="UTF-8" novalidate>
        <h2> Registration </h2>
    
        <div class="form-item">
            <label class="form-item-label" for="login">Your login</label>
            <input class="form-item-input" type="text" id="login" name="login" value="">
            <?php if(isset($_SESSION['newLoginError'])):
            ?>
            <div class="error">
                <?= $_SESSION['newLoginError']?>
            </div>
            <?php
            endif?>
        </div>
        <div class="form-item">
            <label class="form-item-label" for="password">Your password</label>
            <input class="form-item-input" type="password" id="password" name="password" value="">
            <?php if(isset($_SESSION['newPasswordError'])):
            ?>
            <div class="error">
                <?= $_SESSION['newPasswordError']?>
            </div>
            <?php
            endif?>
        </div>
        <div class="form-item">
            <input class="form-item-button" type="submit" value="Registration">
        </div>
        <?php if(isset($_SESSION['regError'])):
            ?>
            <div class="error">
                <?= $_SESSION['regError']?>. Please, try again.
            </div>
            <?php
            endif
        ?>
        <?php if(isset($_SESSION['createTableError'])):
            ?>
            <div class="error">
                <?= $_SESSION['createTableError']?>. Please, try again.
            </div>
            <?php
            endif
        ?>
    </form>
    <?php 
        endif;
    ?>
</main>
<?php
    require_once 'scripts/blocks/footer.php';
?>