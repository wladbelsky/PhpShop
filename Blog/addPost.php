<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 25-Nov-16
 * Time: 15:34
 */

require_once("../Auth/auth.php");
require_once("../Cart/Cart.php");
require_once("../Orders/Orders.php");
require_once("Blog.php");

$auth = new Auth();
$cart = new Cart();
$orders = new Orders();
$blog = new Blog();

if(isset($_POST['postText']))
{
    $blog->addPost($_POST['postText']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>
        tinymce.init({ 
            selector:'textarea',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
            ],
        });
    </script>
</head>
<body>
    <form method="post">
        <textarea name="postText" cols="40" rows="35"></textarea>
        <input type="submit" value="Отправить">
    </form>
</body>
</html>
