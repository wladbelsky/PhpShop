<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 15-Nov-16
 * Time: 18:58
 */

require_once("../Auth/auth.php");
require_once("../Cart/Cart.php");
require_once("../Orders/Orders.php");

$auth = new Auth();
$cart = new Cart();
$orders = new Orders();

if(!$auth->isAuth())
    header("Location: ../Auth/login.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/footer.html");

if(isset($_POST['delivery']) && isset($_POST['address']) && isset($_POST['payment']))
{
    $orders->addOrder($auth->getId(),$_POST['delivery'], $_POST['address'], $_POST['payment']);
    $cart->emptyCart($auth->getId());
    header("Location:../Profile/showOrders.php");
}
if($_POST['addressUpdate'] == 1
    && isset($_POST['Country'])
    && isset($_POST['City'])
    && isset($_POST['Street'])
    && isset($_POST['Building'])
    && isset($_POST['Flat']))
{
    $orders->addDeliveryAddress($auth->getId(), $_POST['Country'], $_POST['City'], $_POST['Street'], $_POST['Building'], $_POST['Flat']);
}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/styles.css">
    <script type="text/javascript" src="/js/elementChildren.js"></script>
</head>
<body>
<div class="content">
<h1>Оформление заказа</h1>
<form method="post">
    <div id="contentHolder">
        <div id="1">
            <h2>Проверка</h2><br>
            <table class="cartTable">
                <?php
                    $res = $cart->get($auth->getId());
                    $send = "<tr><td>Id</td><td>Название</td><td>Количество</td><td>Цена</td></tr>";
                    foreach ($res as $row) {
                        $item = $cart->getItem($row['ItemId']);
                        $item = $item->fetch_assoc();
                        $send .= "<tr><td>" . $item['Id'] . "</td><td>"
                            . $item['ItemName'] . "</td><td>"
                            . $row['Count'] . "</td><td>"
                            . $item['Price'] . "</td></tr>";
                    }
                    echo $send;
                ?>
            </table>
        </div>
        <div id="2" style="display: none">
            <h2>Способ доставки</h2>
            <p><input name="delivery" onchange="Check()" type="radio" value="selfPickup"> Самовывоз </p>
            <p><input name="delivery" onchange="Check()" type="radio" value="delivery"> Доставка курьером </p>
            <p><input name="delivery" onchange="Check()" type="radio" value="post"> Почтой </p>
        </div>
        <div id="3" style="display: none">
            <h2>Адрес доставки</h2>
            <?php
            $res = $orders->getDeliveryAddresses($auth->getId());
            foreach ($res as $row) {
                echo "<input type='radio' onchange=\"Check()\" name='address' value='" . $row['AddressId'] . "'><br>"
                    . $row['UserId'] . "<br>"
                    . $row['AddressId'] . "<br>"
                    . $row['City'] . "<br>"
                    . $row['Street'] . "<br>"
                    . $row['Building'] . "<br>"
                    . $row['Flat'] . "<br>";
            }
            echo "</br><input type=\"button\" value=\"Добавить адрес\" onclick=\"ShowWindow('block')\"><br>";


            ?>
        </div>
        <div id="4" style="display: none">
            <h2>Способ оплаты</h2>
            <p><input name="payment" onchange="Check()" type="radio" value="cash"> Наличными </p>
            <p><input name="payment" onchange="Check()" type="radio" value="paymentSystem"> Через платежную систему </p>
        </div>
    </div>
    <p><input type="button" disabled id="backButton" value="Back" onclick="PrevPage()" >
        <input type="button" value="Next" id="nextButton" onclick="NextPage()">
    </p>
</form>
</div>
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
    <!-- error log here!!! -->
<script type="text/javascript">
    var pageNum = 1;
    const pagesCount = elementChildren(document.getElementById("contentHolder")).length;
    function ShowWindow(state) {
        document.getElementsByClassName('popUpWindow')[0].style.display = state;
    }
    function addAddress() {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.open("POST", "", true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                ShowWindow('none');
                document.body.innerHTML = this.responseText;
                pageNum = 2;
                NextPage();
            }
        };

        var req = 'addressUpdate=' + encodeURIComponent("1")
                    + '&City=' + encodeURIComponent(document.getElementsByName('City')[0].value)
                    + '&Street=' + encodeURIComponent(document.getElementsByName('Street')[0].value)
                    + '&Building=' + encodeURIComponent(document.getElementsByName('Building')[0].value)
                    + '&Flat=' + encodeURIComponent(document.getElementsByName('Flat')[0].value);
        xmlhttp.send(req);
    }
    function NextPage() {
        if(pageNum == pagesCount)
            document.getElementById("nextButton").type = "submit";
        if(pageNum == 2 && document.getElementsByName('delivery')[0].checked)
            pageNum = 4;
        if(pageNum != pagesCount)
            pageNum++;
        if(pageNum == pagesCount) {
            document.getElementById("nextButton").disabled = true;
            document.getElementById("nextButton").value = "Оформить";
            Check();
        }
        else {
            document.getElementById("nextButton").disabled = false;
            document.getElementById("nextButton").value = "Next";
            document.getElementById("backButton").disabled = false;
        }
        for(var i = 1; i <= pagesCount; i++)
        {
            if(i != pageNum)
                document.getElementById(i).style.display = "none";
        }
        document.getElementById(pageNum).style.display = "block";
    }
    function PrevPage() {
        if(pageNum != 1)
            pageNum--;
        if(pageNum == 3 && document.getElementsByName('delivery')[0].checked)
            pageNum = 2;
        if(pageNum == 1)
            document.getElementById("backButton").disabled = true;
        else {
            document.getElementById("backButton").disabled = false;
            document.getElementById("nextButton").disabled = false;
            document.getElementById("nextButton").value = "Next";
        }
        for(var i = 1; i <= pagesCount; i++)
        {
            if(i != pageNum)
                document.getElementById(i).style.display = "none";
        }
        document.getElementById(pageNum).style.display = "block";
    }
    function Check() {
        //j = document.getElementsByName('delivery').length;
        //j = document.getElementsByName('address').length;
        var deliveryOK, addressOK, paymentOK;
        for(var i = 0; i < document.getElementsByName('delivery').length; i++) {
            if (document.getElementsByName('delivery')[i].checked == true)
                deliveryOK = true;
        }
        if(document.getElementsByName('delivery')[0].checked != true) {
            for (i = 0; i < document.getElementsByName('address').length; i++) {
                if (document.getElementsByName('address')[i].checked == true)
                    addressOK = true;
            }
        }
        else { addressOK = true; }
        for(i = 0; i < document.getElementsByName('payment').length; i++) {
            if (document.getElementsByName('payment')[i].checked == true)
                paymentOK = true;
        }
        if(deliveryOK && addressOK && paymentOK)
            document.getElementById("nextButton").disabled = false;
        else if(pageNum == pagesCount)
            document.getElementById("nextButton").disabled = true;
    }

</script>
<script src="/js/addressAuto.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTMWNROOvdakN2jCdYiTy4Zsbty-b4bkQ&signed_in=true&libraries=places&callback=initAutocomplete"
        async defer></script>
</body>
</html>
