<?php

require_once("Cart.php");
require_once("../Auth/auth.php");
$cart = new Cart();
$auth = new Auth();

if(!$auth->isAuth())
    header("Location: ../Auth/login.php");

?>

<html xmlns="http://www.w3.org/1999/html">
<head>
    <link rel="stylesheet" href="/styles.css">
    <script type="text/javascript">
        function DeleteItem(item) {
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "ajaxCart.php", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //successAnim();
                    //alert(this.responseText);
                    if(this.responseText == 0) {
                        document.getElementById("Order").disabled = true;
                    }
                    CartUpdate();
                    TotalUpdate();
                }
            };

            var req = 'itemId=' + encodeURIComponent(item)
                + '&isDel=' + encodeURIComponent("1");


            xmlhttp.send(req);
        }
        function CartUpdate() {
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "ajaxCart.php", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //successAnim();
                    //alert(this.responseText);
                    document.getElementById("cart").innerHTML = this.responseText;
                }
            };

            var req = 'cartUpdate=' + encodeURIComponent("1");
            xmlhttp.send(req);
        }
        function TotalUpdate() {
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "ajaxCart.php", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //successAnim();
                    //alert(this.responseText);
                    document.getElementById("total").innerHTML = "<b>Итого:" + this.responseText + "<b>";
                }
            };

            var req = 'totalUpdate=' + encodeURIComponent("1");
            xmlhttp.send(req); 
        }
        function CountChange(item, count) {
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.open("POST", "ajaxCart.php", true);
            xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //successAnim();
                    //alert(this.responseText);
                    //CartUpdate();
                    TotalUpdate();
                }
            };

            var req = 'CountUpdate=' + encodeURIComponent("1")
                + '&itemId=' + encodeURIComponent(item)
                + '&newCount=' + encodeURIComponent(count);
            xmlhttp.send(req);
        }
        window.onload = function () {
            CartUpdate();
        }
    </script>
</head>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 31-Oct-16
 * Time: 19:02
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/header.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Layouts/footer.html");
?>
<div class="content">
<table class="cartTable" id="cart">
</table>
<br>
<div id="total"><b>Итого:<?php echo $cart->totalPrice($auth->getId());?></b></div>
<form action="/Orders/processOrder.php">
    <input type="submit" id="Order" value="Оформить" <?php $res = $cart->get($auth->getId()); if(!$res->num_rows) echo "disabled"; ?>>
</form>
</div>
</body
</html>
