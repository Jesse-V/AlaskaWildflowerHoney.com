<?php

require_once('Order.php');

class SuppliesOrder extends Order
{
    private $orderedItems_ = array();
    public $pickupLocation_;


    function __construct($pickup)
    {
        $this->pickupLocation_ = $pickup;
    }



    public function addItem($item)
    {
        array_push($this->orderedItems_, $item);
    }



    public function getItems()
    {
        return $this->orderedItems_;
    }



    public function getTotal()
    {
        $total = 0;
        foreach ($this->orderedItems_ as $item)
            $total += $item->price_ * $item->quantity_;
        return $total;
    }
}




class SupplyItem
{
    public $name_;
    public $description_;
    public $imageURL_;
    public $price_;
    public $quantity_;

    public $groupName_;
    public $groupDescription_;


    function __construct($name, $desc, $groupName, $groupDesc, $imageURL, $price, $quantity)
    {
        $this->name_        = $name;
        $this->description_ = $desc;
        $this->imageURL_    = $imageURL;
        $this->price_       = $price;
        $this->quantity_    = $quantity;

        $this->groupName_        = $groupName;
        $this->groupDescription_ = $groupDesc;
    }
}

?>
