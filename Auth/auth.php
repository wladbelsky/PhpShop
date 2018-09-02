<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 23-Oct-16
 * Time: 13:59
 */

class Auth
{

    private $mysqli;
    
    function __construct() {
        session_start();
        $this->mysqli = $this->connect();
    }
    
    private function connect()
    {
        $mysqli = new \mysqli("localhost", "myhost", "myhost", "Main");
        $mysqli->set_charset("utf8");
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

        }
        return $mysqli;
    }
    
    public function isAuth() {
        if (isset($_SESSION["is_auth"])) {
            return $_SESSION["is_auth"];
        }
        else return false;
    }

    public function login(string $email, string $pass)
    {
        /*$res = $this->mysqli->query("SELECT Id, Phone, Name FROM Main.Users WHERE Email='" . $email . "' AND Password='". md5($pass) ."';");
        if ($res->num_rows == 1){
            $_SESSION['Email'] = $email;
            $row = $res->fetch_assoc();
            $_SESSION['userId'] = $row['Id'];
            $_SESSION['Phone'] = $row['Phone'];
            $_SESSION['Name'] = $row['Name'];
            $_SESSION['is_auth'] = true;
            return true;
        }
        else {return false;}*/
        $res = $this->mysqli->query("SELECT Id, Phone, Name, Password FROM Main.Users WHERE Email='" . $email . "';");
        $row = $res->fetch_assoc();
        if(password_verify($pass, $row['Password']))
        {
            $_SESSION['Email'] = $email;
            $_SESSION['userId'] = $row['Id'];
            $_SESSION['Phone'] = $row['Phone'];
            $_SESSION['Name'] = $row['Name'];
            $_SESSION['is_auth'] = true;
            return true;
        }
    }

    public function register($email, $name, $phone, $pass)
    {
        $passHash = password_hash($pass, PASSWORD_DEFAULT);
        $this->mysqli->query("INSERT INTO Main.Users (Email, Name, Password, Phone) VALUES ('$email', '$name', '$passHash', '$phone')");
    }

    public function getEmail(){
        if($this->isAuth()){
            return $_SESSION['Email'];
        }
        else return false;
    }

    public function getId()
    {
        if($this->isAuth()){
            return $_SESSION['userId'];
        }
        else return false;
    }
    
    public function getPhone()
    {
        if($this->isAuth()){
            return $_SESSION['Phone'];
        }
        else return false;
    }
    
    public function getName()
    {
        if($this->isAuth()){
            return $_SESSION['Name'];
        }
        else return false;
    }

    public function logOut()
    {
        $_SESSION = array();
        session_destroy();
    }

}