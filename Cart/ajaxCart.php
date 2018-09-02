<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 07-Nov-16
 * Time: 17:54
 */

require_once("../Auth/auth.php");
require_once("Cart.php");

$mysqli = new mysqli("localhost", "myhost", "myhost", "Main");
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

}



$cart = new Cart();
$auth = new Auth();

if($auth->isAuth()
    && $_POST['cartUpdate'] == 1)
{
    $res = $cart->get($auth->getId());
    if($res->num_rows != 0) {
        $send = "<tr><td>Id</td><td>Название</td><td>Количество</td><td>Цена</td><td>Удалить</td></tr>";
        foreach ($res as $row) {
            $item = $cart->getItem($row['ItemId']);
            $item = $item->fetch_assoc();
            $send .= "<tr><td>" . $item['Id'] . "</td><td>"
                . $item['ItemName'] . "</td><td>"
                . "<input type=\"number\" min=\"1\" value=\"" . $row['Count'] . "\" onchange=\"CountChange(" . $item['Id'] . ", this.value);\" >"  . "</td><td>"
                . $item['Price'] . "</td><td>"
                . "<input type=\"button\" value=\"remove\" id=\"delButton\" onclick='DeleteItem(" . $item['Id'] . ");' >" . " </td></tr>";
        }
    }
    else { $send = "Пусто!"; }
    echo $send;
}

else if(isset($_POST['count'])
    && isset($_POST['itemId'])
    && $auth->isAuth())
{
    $userId = $auth->getId();
    $itemId = $_POST['itemId'];
    $count = $_POST['count'];
    if($cart->isThereItem($userId, $itemId) == 0)
    {
        $cart->add($userId, $itemId, $count);
    }
    else if($cart->isThereItem($userId, $itemId) == 1)
    {
        $cart->changeCount($userId, $itemId, $count);
    }
}

else if (isset($_POST['itemId'])
    && $_POST['isDel'] == 1
    && $auth->isAuth())
{
    $userId = $auth->getId();
    $itemId = $_POST['itemId'];
    $cart->remove($userId, $itemId);
    $res = $cart->get($auth->getId());
    echo $res->num_rows;
}

else if($auth->isAuth()
    && $_POST['totalUpdate'] == 1)
{
    echo $cart->totalPrice($auth->getId());
}
else if($auth->isAuth()
    && isset($_POST['itemId'])
    && $_POST['CountUpdate'] == 1)
{
    $cart->changeCount($auth->getId(), $_POST['itemId'], $_POST['newCount']);
}