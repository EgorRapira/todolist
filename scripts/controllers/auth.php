<?php
    session_start(); // старт сессии
    require_once "{$_SERVER['DOCUMENT_ROOT']}/functions/functions.php"; // подключение всех функций
    connectSettings(); // получение подключения к базе данных
    resetRegisterAndAuthErrors(); // сброс ошибок


    // $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING); // обработка строки логин
    // $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING); // обработка строки пароль

    // if (strlen($login) === 0 || strlen($login) > 30) { // если длина логина = 0 или больше 30, то
    //     $_SESSION['loginError'] = "Incorrect login";
    //     redirectToIndex(); 
    // } elseif (strlen($password) < 4) { // если длина пароля меньше 4, то
    //     $_SESSION['passwordError'] = "Incorrect password";
    //     redirectToIndex();
    // }
    // $password = md5($password.$userSalt); // хэширование пароля

    // $connect = new mysqli($host, $mySqlUser, $mySqlPassword, $mysqlDB); // подключение к базе данных
    // if($connect->connect_error) { // проверка ошибок при подключении
    //     echo "Error number:".$connect->connect_errno.'<br>';
    //     echo "Error:".$connect->connect_error;
    //     $_SESSION['authError'] = "Connection error";
    //     redirectToIndex();
    // }

    // $query = "SELECT `login`,`password` FROM `users` WHERE `login` = '$login'"; // запрос на выборку юзера по логину и паролю
    // $result = $connect->query($query);
    // $connect->close();
    // if($result->num_rows === 0) { // если в ответе нет строк, то
    //     $_SESSION['authError'] = "User wasn't find";
    // } else {
    //     $row = $result->fetch_assoc();
    //     if ($row['password'] == $password) {
    //         $_SESSION['auth'] = $login;
    //         redirectToCabinet();
    //     }
    //     else {
    //         $_SESSION['authError'] = "Incorrect password";
    //         redirectToIndex();
    //     }
    // }