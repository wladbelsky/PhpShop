<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 15-Nov-16
 * Time: 17:50
 */

require_once("../Cart/Cart.php");
require_once("../Auth/auth.php");

$auth = new Auth();
$cart = new Cart();

if(!$auth->isAuth())
    header("Location: ../Auth/login.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/footer.html");
?>
<div class="content">
<?php
//echo $auth->getEmail() . "<br>" . $auth->getId() . "<br>" . $auth->getPhone() . "<br>";
?>
<h1><?php echo $auth->getName(); ?></h1>    
<h2><?php echo $auth->getEmail(); ?></h2>
<h2><?php echo $auth->getPhone(); ?></h2>    
<a href="delivery.php">Адреса доставки</a><br>
<a href="showOrders.php">Мои заказы</a> 
</div>