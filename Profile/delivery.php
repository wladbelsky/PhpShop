<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 15-Nov-16
 * Time: 17:57
 */

require_once("../Auth/auth.php");
require_once("../Cart/Cart.php");
require_once("../Orders/Orders.php");

$auth = new Auth();
$cart = new Cart();
$orders = new Orders();

if(!$auth->isAuth())
    header("Location: ../Auth/login.php");

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/styles.css">
    <script type="text/javascript">
        function ShowWindow(state) {
            document.getElementsByClassName('popUpWindow')[0].style.display = state;
        }
    </script>
</head>
<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php"); 
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/footer.html"); ?>
<div class="content">
<input type="button" value="Добавить адрес" onclick="ShowWindow('block')"><br>
<div style="display: none" class="popUpWindow">
    <form method="POST">
        <input type="button" value="X" onclick="ShowWindow('none')" style="position:absolute; right: 0%; top:0%;"><br>
        <div id="locationField" >
            <input id="autocomplete" style="width: 100%" placeholder="Введите адрес"
                   onFocus="geolocate()" type="text">
        </div>

        <table id="address">
            <tr>
                <td>Страна</td>
                <td><input class="field" id="country" name="Country" disabled="true"></td>
            </tr>
            <tr>
                <td>Город</td>
                <td><input class="field" id="locality" name="City" disabled="true"></td>
            </tr>
            <tr>
                <td>Адрес</td>
                <td><p><input class="field" id="route" name="Street" disabled="true"></p><p>Дом
                    <input class="field" id="street_number" name="Building" disabled="true"></p><p>Квартира <input name="Flat" ></p></td>
            </tr>
        </table>
        
        <input name="submit" type="submit" value="Добавить">
    </form>
</div>
<?php

if(isset($_POST['submit'])
        && isset($_POST['Country'])
        && isset($_POST['City'])
        && isset($_POST['Street'])
        && isset($_POST['Building'])
        && isset($_POST['Flat']))
{
    $orders->addDeliveryAddress($auth->getId(), $_POST['Country'], $_POST['City'], $_POST['Street'], $_POST['Building'], $_POST['Flat']);
}
if(isset($_POST['delId']))
{
    $orders->deleteDeliveryAddress($auth->getId(), $_POST['delId']);
}

if($auth->isAuth())
{
    $res = $orders->getDeliveryAddresses($auth->getId());
    foreach ($res as $row)
    {
        $orderCheck = $orders->getOrders($auth->getId());
        $dis = '';
        foreach ($orderCheck as $check)
        {
            if($check['AddressId'] == $row['AddressId']
                && $check['Status'] != 2) {
                $dis = "disabled";
                break;
            }
            else
                $dis = '';
        }
        echo "#" . $row['AddressId'] . "<br>"
            . $row['Country'] . "<br>"
            . $row['City'] . "<br>"
            . $row['Street']
            . $row['Building']
            . $row['Flat'] . "<br>"
            . "<form method='post'><button type='submit' name='delId' value='". $row['AddressId'] ."' ". $dis ." >X</button></form>";
        if($dis == 'disabled')
            echo " Вы не можете удалить этот адрес сейчас, так как на него еще не доставлен заказ<br>";
    }
}

?>
<script src="/js/addressAuto.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTMWNROOvdakN2jCdYiTy4Zsbty-b4bkQ&signed_in=true&libraries=places&callback=initAutocomplete"
        async defer></script>
</div>
</body>
</html>
<?php unset($_POST);?>