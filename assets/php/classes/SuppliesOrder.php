<?php

require_once(__DIR__.'/Order.php');

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



    public function changeQuantityOnItem($itemID, $newQuantity)
    {
        if (!is_numeric($itemID) ||         //sanitization check
            !is_numeric($newQuantity) || $newQuantity > 500)
            return false;

        //lookup by itemID, find index in array
        foreach ($this->orderedItems_ as $index => $item)
            if ($item->itemID_ == $itemID)
                break;

        if ($newQuantity == 0)
        {
            unset($this->orderedItems_[$index]);
            $this->orderedItems_ = array_values($this->orderedItems_);
        }
        else
            $this->orderedItems_[$index]->quantity_ = $newQuantity;

        return true;
    }



    public function removeItemByID($itemID)
    { //https://stackoverflow.com/questions/369602/delete-an-element-from-an-array

        if (!is_numeric($itemID)) //sanitization check
            return false;

        $countBefore = count($this->orderedItems_);
        $this->changeQuantityOnItem($itemID, 0);
        return $countBefore != count($this->orderedItems_);
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
    public $itemID_;
    public $name_;
    public $description_;
    public $imageURL_;
    public $price_;
    public $quantity_;

    public $groupName_;
    public $groupDescription_;


    function __construct($itemID, $name, $desc, $groupName, $groupDesc, $imageURL, $price, $quantity)
    {
        $this->itemID_        = $itemID;
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
