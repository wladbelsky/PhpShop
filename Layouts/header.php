<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Auth/auth.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Cart/Cart.php");

$auth = new Auth();
$cart = new Cart();

if(isset($_POST["logout"])){
    $auth->logOut();
}
?>
   	<div id="topBar">
    	<img id="logo" src="/images/logo.jpg" draggable="false" style="-moz-user-select: none;">
        <div class="topElement">
        	<div style="line-height:0.1; padding-right: 10px;;">
                <p><?php echo($auth->getEmail()); ?></p>
                <p><?php echo("В вашей корзине " . $cart->get($auth->getId())->num_rows . " товаров на сумму " . $cart->getTotalPrice($auth->getId()) . "RUB");?></p>
                <p>
                  <form>
                    <button type="submit" name="logout">Выход</button>
                  </form>
                </p>
            </div>
        	<div style="padding-top:10px;"> <img src="/images/user.png"  height="50">
            </div>
        </div>
        <div class="topElement">BUTTON</div>
    </div>
        <div id="menu">
            <ul id="nav">
                <li>
                    <a href="../designSample.html">Главная</a>
                </li>
                <li>
                    <a href="../designSample_about.html">О бренде</a>
                </li>
                <li>
                    <a href="#">Колекции</a>
                    <ul>
                        <li><a href="#">Monochrome</a></li>
                        <li><a href="#">ss16</a></li>
                        <li><a href="#">pw16/17</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Магазины</a>
                    <ul>
                        <li><a href="https://vk.com/sharkovichdesign">Мы в ВК</a></li>
                        <li><a href="https://vk.com/freedomstore_ru">FREEDOM STORE в ВК</a></li>
                        <li><a href="https://www.stylewe.com/designer/olga-sharkovich--356">Мы на stylewe.com</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Контакты</a>
                </li>
            </ul>
        </div>