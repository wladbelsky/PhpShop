<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 26-Oct-16
 * Time: 03:57
 */

class Cart
{
    private $mysqli;
    
    function __construct()
    {
        $this->mysqli = $this->connect();
    }

    private function connect()
    {
        $mysqli = new \mysqli("localhost", "myhost", "myhost", "Main");
        $mysqli->set_charset("utf8");
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

        }
        else
        { return $mysqli; }
    }
    
    public function add($userId, $itemId, $count)
    {
        if($count <= 0)
            $count = 1;
        $this->mysqli->query("INSERT INTO Main.Cart (UserId, ItemId, Count) VALUES ($userId, $itemId, $count)");
}

    public function remove($userId, $itemId)
    {
        $this->mysqli->query("DELETE FROM Main.Cart WHERE UserId = $userId AND ItemId = $itemId");
    }

    public function get($userId)
    {
        return $this->mysqli->query("SELECT * FROM Main.Cart WHERE UserId = $userId");
    }
    
    public function getItemFromCart($userId, $itemId)
    {
        return $this->mysqli->query("SELECT * FROM Main.Cart WHERE UserId = $userId AND ItemId = $itemId");
    }

    public function changeCount($userId, $itemId, $newCount)
    {
        if($newCount <= 0)
            $newCount = 1;
        $this->mysqli->query("UPDATE Main.Cart SET Count = $newCount WHERE UserId = $userId AND ItemId = $itemId");
    }

    public function getItem($itemId)
    {
        return $this->mysqli->query("SELECT * FROM Main.Items WHERE Id='$itemId'");
    }
    
    public function isThereItem($userId, $itemId)
    {
        $res = $this->mysqli->query("SELECT * FROM Main.Cart WHERE UserId = $userId AND ItemId = $itemId");
        return $res->num_rows;
    }
    
    public function totalPrice($userId)
    {
        $res = $this->mysqli->query("SELECT Cart.ItemId, Cart.Count FROM Main.Cart WHERE UserId = $userId");
        $total = 0;
        foreach ($res as $row)
        {
            $item = $this->getItem($row['ItemId']);
            $item = $item->fetch_assoc();
            $total += $item['Price'] * $row['Count'];
        }
        $_SESSION['totalPrice'] = $total;
        return $total;
    }
    
    public function getTotalPrice()
    {
        return $_SESSION['totalPrice'];
    }

    public function emptyCart($userId)
    {
        $this->mysqli->query("DELETE FROM Main.Cart WHERE UserId = $userId");
    }

}