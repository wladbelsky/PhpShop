<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 20-Nov-16
 * Time: 19:43
 */
?>
<link rel="stylesheet" href="/styles.css">
<?php
require_once("../Auth/auth.php");
require_once("../Cart/Cart.php");
require_once("../Orders/Orders.php");

$auth = new Auth();
$cart = new Cart();
$orders = new Orders();

if(!$auth->isAuth())
    header("Location: ../Auth/login.php");
if($auth->getEmail() == "admin")
    header("Location: ../Auth/orderManager.php");

$Orders = $orders->getOrders($auth->getId());
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/footer.html");

?>
<div class="content">
<?php
echo "<h1>Принято</h1>";
echo "<table class='cartTable'><tr><td>№</td><td>Товар</td><td>Оплата</td><td>Доставка</td></tr>";

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
        echo "</td></tr>";
    }
}
echo "</table>";

echo "<h1>В процессе</h1>";
echo "<table class='cartTable'><tr><td>№</td><td>Товар</td><td>Оплата</td><td>Доставка</td></tr>";

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
        echo "</td></tr>";
    }
}
echo "</table>";

echo "<h1>Завершено</h1>";
echo "<table class='cartTable'><tr><td>№</td><td>Товар</td><td>Оплата</td><td>Доставка</td></tr>";

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
        echo "</td></tr>";
    }
}
echo "</table>";
?>
</div>
    