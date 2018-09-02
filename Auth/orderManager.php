<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 21-Nov-16
 * Time: 18:17
 */

require_once("../Auth/auth.php");
require_once("../Cart/Cart.php");
require_once("../Orders/Orders.php");
?>
<head>
    <link rel="stylesheet" href="/styles.css">
    <script type="text/javascript">
        function ChangeStatus(orderId, status) {
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //successAnim();
                    //alert(this.responseText);
                    ListUpdate();
                }
            };

            var req = 'orderId=' + encodeURIComponent(orderId)
                + '&status=' + encodeURIComponent(status);


            xmlhttp.send(req);
        }
        function ListUpdate() {
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //successAnim();
                    //alert(this.responseText);
                    document.body.innerHTML = this.responseText;
                }
            };
            
            xmlhttp.send();
        }
    </script>
</head>

<?php
$auth = new Auth();
$cart = new Cart();
$orders = new Orders();

if(!$auth->isAuth())
    header("Location: ../Auth/login.php");
if($auth->getEmail() != "admin")
    header("Location: ../index.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/footer.html");
?>
<div class="content">
<?php
if(isset($_POST['orderId']) && isset($_POST['status']))
{
    $orders->changeStatus($_POST['orderId'], $_POST['status']);
}
$Orders = $orders->getOrders();
echo "<div class=\"contentHolder\">";
echo "<h1>Принято</h1>";
echo "<table class='cartTable'><tr><td>№</td><td>Товар</td><td>Оплата</td><td>Доставка</td><td>Действие</td></tr>";

foreach ($Orders as $Order)
{
    if($Order['Status'] == 0) // 0 is 'accepted'
    {
        echo "<tr><td>" . $Order['OrderId'] . "</td><td>";
        $items = $orders->getItems($Order['OrderId']);
        $count = $orders->getCount($Order['OrderId']);
        $i = 0;
        foreach ($items as $item)
        {
            $itemName = $cart->getItem($item);
            $itemName = $itemName->fetch_assoc();
            echo $itemName['ItemName'];
            echo " x" . $count[$i] . "<br>";
            $i++;
        }
        echo "Итого: " . $orders->getTotal($Order['OrderId']);
        echo "</td><td>";
        switch ($Order['PaymentMethod'])
        {

            case "cash":
                echo "Наличные";
                break;
            case "paymentSystem":
                echo "Через платежную систему";
                break;//in progress!
        }
        echo "</td><td>";
        switch ($Order['DeliveryMethod'])
        {
            case "post":
                echo "Почтой";
                break;
            case "delivery":
                echo "Курьером";
                break;
            case "selfPickup":
                echo "Самовывоз";
                break;
        }
        echo "</td><td>";
        echo "<input type='button' onclick='ChangeStatus(". $Order['OrderId'] .", 1)' value='В обработке'>";
        echo "</td></tr>";
    }
}
echo "</table>";

echo "<h1>В процессе</h1>";
echo "<table class='cartTable'><tr><td>№</td><td>Товар</td><td>Оплата</td><td>Доставка</td><td>Действие</td></tr>";

foreach ($Orders as $Order)
{
    if($Order['Status'] == 1) // 1 is 'in progress'
    {
        echo "<tr><td>" . $Order['OrderId'] . "</td><td>";
        $items = $orders->getItems($Order['OrderId']);
        $count = $orders->getCount($Order['OrderId']);
        $i = 0;
        foreach ($items as $item)
        {
            $itemName = $cart->getItem($item);
            $itemName = $itemName->fetch_assoc();
            echo $itemName['ItemName'];
            echo " x" . $count[$i] . "<br>";
            $i++;
        }
        echo "Итого: " . $orders->getTotal($Order['OrderId']);
        echo "</td><td>";
        switch ($Order['PaymentMethod'])
        {

            case "cash":
                echo "Наличные";
                break;
            case "paymentSystem":
                echo "Через платежную систему";
                break;//in progress!
        }
        echo "</td><td>";
        switch ($Order['DeliveryMethod'])
        {
            case "post":
                echo "Почтой";
                break;
            case "delivery":
                echo "Курьером";
                break;
            case "selfPickup":
                echo "Самовывоз";
                break;
        }
        echo "</td><td>";
        echo "<input type='button' onclick='ChangeStatus(". $Order['OrderId'] .", 0)' value='Принято'>";
        echo "<br>";
        echo "<input type='button' onclick='ChangeStatus(". $Order['OrderId'] .", 2)' value='Завершено'>";
        echo "</td></tr>";
    }
}
echo "</table>";

echo "<h1>Завершено</h1>";
echo "<table class='cartTable'><tr><td>№</td><td>Товар</td><td>Оплата</td><td>Доставка</td><td>Действие</td></tr>";

foreach ($Orders as $Order)
{
    if($Order['Status'] == 2) // 2 is 'complited'
    {
        echo "<tr><td>" . $Order['OrderId'] . "</td><td>";
        $items = $orders->getItems($Order['OrderId']);
        $count = $orders->getCount($Order['OrderId']);
        $i = 0;
        foreach ($items as $item)
        {
            $itemName = $cart->getItem($item);
            $itemName = $itemName->fetch_assoc();
            echo $itemName['ItemName'];
            echo " x" . $count[$i] . "<br>";
            $i++;
        }
        echo "Итого: " . $orders->getTotal($Order['OrderId']);
        echo "</td><td>";
        switch ($Order['PaymentMethod'])
        {

            case "cash":
                echo "Наличные";
                break;
            case "paymentSystem":
                echo "Через платежную систему";
                break;//in progress!
        }
        echo "</td><td>";
        switch ($Order['DeliveryMethod'])
        {
            case "post":
                echo "Почтой";
                break;
            case "delivery":
                echo "Курьером";
                break;
            case "selfPickup":
                echo "Самовывоз";
                break;
        }
        echo "</td><td>";
        echo "<input type='button' onclick='ChangeStatus(". $Order['OrderId'] .", 0)' value='Принято'>";
        echo "<br>";
        echo "<input type='button' onclick='ChangeStatus(". $Order['OrderId'] .", 1)' value='В обработке'>";
        echo "</td></tr>";
    }
}
echo "</table></div>";
?>
</div>    