<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 16-Oct-16
 * Time: 09:13
 */

require_once("Cart/Cart.php");
require_once("Auth/auth.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php");
$auth = new Auth();
$cart = new Cart();

$mysqli = new mysqli("localhost", "myhost", "myhost", "Main");
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

}
$mysqli->set_charset("utf8");
?>
<div class="content">
    <div id="previewHolder">
        <div>
            <img style="width: 300px" src="img/batman-logo-big.gif"><!--Real Image Here! -->
        </div>
        <div id="itemDetails">
<?php
if(isset($_GET["item"])) {
    $res = $mysqli->query("SELECT * FROM Main.Items WHERE Id=" . $_GET['item']);
    $row = $res->fetch_assoc();
    if($row['Id'] != ''){
        echo "<h1>" . $row['ItemName'] . "</h1><h2>" . $row['Price'] . "</h2>";
    }
    else {
        echo "404. Товар не найден! Вернитесь на главную.";//В дальнейшем заменить на include или нормальную станицу аля 404
    }
}
else {//Протестировать
    echo "404. Товар не найден! Вернитесь на главную.";//В дальнейшем заменить на include или нормальную станицу аля 404
}
?>
<head>
    <script type="text/javascript">
        function AddToCart() {
            if(document.getElementById("count").value <= 0)
                document.getElementById("count").value = 1;
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "Cart/ajaxCart.php", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    successAnim();
                    //alert(this.responseText);
                }
            };

            var req = 'itemId=' + encodeURIComponent("<?php echo $_GET["item"]; ?>")
                + '&count=' + encodeURIComponent(document.getElementById("count").value);


            xmlhttp.send(req);
        }
        function successAnim() {
            document.getElementById("cartButton").value = "Добавленно";
            setTimeout(function(){document.getElementById("cartButton").value = "Изменить";}, 2000);
            //document.getElementById("cartButton").style += "background = green";
        }
    </script>
</head>
<br>
<input type="button" 
       value="<?php if(!$cart->isThereItem($auth->getId(),$_GET['item'])) { echo "В корзину"; } else { echo "Изменить"; } ?>" 
       id="cartButton" <?php if(!$auth->isAuth()){echo "disabled title=\"Необходимо зарегистрироваться\"";}?> 
       onclick='AddToCart();'
    >
<input type="number"
       value="<?php 
       if($cart->isThereItem($auth->getId(),$_GET['item']))
       {
           $res = $cart->getItemFromCart($auth->getId(), $_GET['item']);
           $row = $res->fetch_assoc();
           echo $row['Count'];
       }
       else { echo "1"; }
       ?>"
       min="1" id="count" >
    </div>
    </div>
</div>





