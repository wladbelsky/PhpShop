<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 22-Oct-16
 * Time: 17:23
 */


/*
$mysqli = new mysqli("localhost", "myhost", "myhost", "Main");
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

}*/
//Sessions Auth

require_once("auth.php");
if(isset($_POST['Email']) || isset($_POST['password'])) {
    $auth = new Auth();
    if($auth->login($_POST['Email'], $_POST['password'])) 
        header("Location: ../index.php");
    else
        echo "Неверное имя пользователя или пароль";
}
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/footer.html");
?>
<div class="content">
    <form method="POST">
    
        <div class="formField"> <label>Email:</label> <input name="Email" type="email"></div>
    
        <div class="formField"> <label>Пароль:</label> <input name="password" type="password"></div>
    
        <input name="submit" type="submit" value="Войти">
    
    </form>
</div>