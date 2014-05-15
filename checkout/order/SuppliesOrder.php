<?php

require_once('Order.php');

class SuppliesOrder extends Order
{
    private $orderedItems_ = array();
    private $pickupLocation_;


    function __construct($pickup)
    {
        $this->pickupLocation_ = $pickup;
    }



    public function addItem($item)
    {
        array_push($this->orderedItems_, $item);
    }



    public function getTotal()
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
