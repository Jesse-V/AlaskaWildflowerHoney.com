<?php

require_once('Order.php');

class SuppliesOrder extends Order
{
    private $orderedItems_ = array();

    protected function _getTotal()
    {
        return 0.00;
    }
}

class SupplyItem
{
    private $name_;
    private $description_;
    private $imageURL_;
    private $price_;
    private $quantity_;

    private $groupName_;
    private $groupDescription_;
}

?>
