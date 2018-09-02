<?php

/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 15-Nov-16
 * Time: 18:02
 */

class Orders
{
    private $mysqli;

    function __construct()
    {
        $this->mysqli = $this->connect();
    }

    private function connect()
    {
        $mysqli = new \mysqli("localhost", "myhost", "myhost", "Main");
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;

        }
        else
        { return $mysqli; }
    }
    //Addresses
    public function addDeliveryAddress($userId, $country , $city, $street, $building, $flat)
    {
        $res = $this->mysqli->query("SELECT AddressId FROM Main.DeliveryAddresses WHERE UserId = $userId ORDER BY AddressId DESC");
        if($res->num_rows != 0)
        {
            $row = $res->fetch_assoc();
            $addressId = $row['AddressId'] + 1;
        }
        else
            $addressId = 1;
        $this->mysqli->query("INSERT INTO Main.DeliveryAddresses (UserId, Country,  AddressId , City, Street, Building, Flat) 
                                            VALUES ($userId, '$country' , $addressId , '$city', '$street', '$building', '$flat')");
    }
    
    public function deleteDeliveryAddress($userId, $addressId)
    {
        $this->mysqli->query("DELETE FROM Main.DeliveryAddresses WHERE UserId = $userId AND AddressId = $addressId");
    }
    
    public function getDeliveryAddresses($userId)
    {
        return $this->mysqli->query("SELECT * FROM Main.DeliveryAddresses WHERE UserId = $userId");
    }
    //Orders Processing
    public function addOrder($userId, $delivery, $address, $payment)
    {
        $cart = new Cart();
        $res = $cart->get($userId);
        
        $items = null;
        
        foreach ($res as $row)
        {
            $items .= $row['ItemId'] . ',' . $row['Count'] . ';';
        }
        $this->mysqli->query("INSERT INTO Main.Orders (UserId, Items, DeliveryMethod, AddressId, PaymentMethod) 
                                          VALUES ($userId, '$items', '$delivery', $address, '$payment');");
        //ok!
    }
    
    public function getOrders($userId = false)
    {
        if($userId)
            return $this->mysqli->query("SELECT * FROM Main.Orders WHERE UserId = $userId");
        else
            return $this->mysqli->query("SELECT * FROM Main.Orders");
    }

    public function getItems($orderId)
    {
        $res = $this->mysqli->query("SELECT Items FROM Main.Orders WHERE OrderId = $orderId");
        if($res->num_rows == 1)
        {
            $row = $res->fetch_assoc();
            $itemsAndCount = explode(';', $row['Items']);
            $items = array();
            foreach ($itemsAndCount as $item)
            {
                $arr = explode(',', $item);
                $items[] = $arr['0'];
            }
            unset($items[count($items) - 1]);
            return $items;
        }
        return false;

    }

    public function getCount($orderId)
    {
        $res = $this->mysqli->query("SELECT Items FROM Main.Orders WHERE OrderId = $orderId");
        if($res->num_rows == 1)
        {
            $row = $res->fetch_assoc();
            $itemsAndCount = explode(';', $row['Items']);
            $items = array();
            foreach ($itemsAndCount as $item)
            {
                $arr = explode(',', $item);
                $items[] = $arr['1'];
            }
            return $items;
        }
        return false;

    }

    public function getTotal($orderId)
    {
        $count = $this->getCount($orderId);
        $items = $this->getItems($orderId);
        $cart = new Cart();
        $i = 0;
        $total = 0;
        foreach ($items as $item)
        {
            $temp = $cart->getItem($item[0]);
            $temp = $temp->fetch_assoc();
            $total += $temp['Price'] * $count[$i];
            $i++;
        }
        return $total;
    }
    
    public function changeStatus($orderId, $status)
    {
        $this->mysqli->query("UPDATE Main.Orders SET Status = $status WHERE OrderId = $orderId");
    }

}