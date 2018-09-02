<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 16-Oct-16
 * Time: 10:03
 */

$mysqli = new mysqli("localhost", "myhost", "myhost", "Main");
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

}

//

require_once("auth.php");
$auth = new Auth();

//
$err = array();
if(isset($_POST['email'])||isset($_POST['password'])) {
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $err[] = "Email указан не верно<br>";
    }
    if (!preg_match('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/', $_POST['phone'])) {
        $err[] = "Номер телефона указан не верно<br>";
    }
    $res = $mysqli->query("SELECT Id FROM Main.Users WHERE Email='" . $_POST['email'] . "';");
    if ($res->num_rows > 0
        && $_POST['email'] != ""
        && $_POST['password'] != ""
    ) {
        $err[] = "Пользователь с таким Email уже существует в базе данных<br>";
    }
    if (strlen($_POST['email']) <= 6 or strlen($_POST['password']) >= 30) {
        $err[] = "Пароль должен быть не меньше 6-х символов и не больше 30<br>";
    }
    if(!preg_match("/([А-Яа-яЁе]+) ([А-Яа-яЁе]+)( )?([А-Яа-яЁе]+)?/", $_POST['NameSurname']))
    {
        $err[] = "Введите корректные ФИО<br>";
    }
    if (count($err) == 0) {
        $email = $_POST['email'];
        $name = $_POST['NameSurname'];
        $phone = $_POST['phone'];
        $pass = $_POST['password'];
        $auth->register($email, $name, $phone, $pass);
        header("Location: login.php");
    } else {
        echo "При регистрации возникли следующие ошибки:<br>";
        foreach ($err AS $error) {

            print $error . "<br>";

        }
    }
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/footer.html");
?>



<div class="content">
    <form method="POST">
            <div class="formField"> <label>Электорнная почта:</label> <input name="email" type="email"></div>

            <div class="formField"> <label>ФИО:</label> <input type="text" name="NameSurname"></div>

            <div class="formField"> <label>Мобильный телефон:</label> <input name="phone" type="tel"></div>

            <div class="formField"> <label>Пароль:</label> <input name="password" type="password"></div>
        <input name="submit" type="submit" value="Зарегистрироваться">
        <br>
        
    </form>
</div>
<?php unset($_POST);?>