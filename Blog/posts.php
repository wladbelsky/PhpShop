<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 25-Nov-16
 * Time: 16:04
 */

require_once("../Auth/auth.php");
require_once("../Cart/Cart.php");
require_once("../Orders/Orders.php");
require_once("Blog.php");

$auth = new Auth();
$cart = new Cart();
$orders = new Orders();
$blog = new Blog();

$posts = $blog->getPosts();

foreach ($posts as $post)
{
    echo $post['Text'];
}