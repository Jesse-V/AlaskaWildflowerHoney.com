<?php

require_once(__DIR__.'/Order.php');
require_once(__DIR__.'/BeePrices.php');

class BeeOrder extends Order
{
    private $nSIts_ = 0, $nDIts_ = 0;
    private $nSCarnis_ = 0, $nDCarnis_ = 0;
    private $nItQ_   = 0, $nCarniQ_ = 0;
    private $pickupPoint_, $customLoc_, $notes_;


    public function __construct($nSingleItalians, $nDoubleItalians, $nSingleCarniolans,
        $nDoubleCarniolans, $nItalianQueens, $nCarniolanQueens, $pickupPoint,
        $customPickupLoc, $notes)
    {
        $this->nSIts_ = htmlentities(strip_tags($nSingleItalians));
        $this->nDIts_ = htmlentities(strip_tags($nDoubleItalians));

        $this->nSCarnis_ = htmlentities(strip_tags($nSingleCarniolans));
        $this->nDCarnis_ = htmlentities(strip_tags($nDoubleCarniolans));

        $this->nItQ_    = htmlentities(strip_tags($nItalianQueens));
        $this->nCarniQ_ = htmlentities(strip_tags($nCarniolanQueens));

        $this->pickupPoint_ = htmlentities(strip_tags($pickupPoint));
        $this->customLoc_   = htmlentities(strip_tags($customPickupLoc));
        $this->notes_       = htmlentities(strip_tags($notes));
    }



    public function removeOrderByID($id) //matches ids in getPackageOrder()
    {
        switch ($id)
        {
            case 1:
                $this->nSIts_ = 0;
                return true;
            case 2:
                $this->nDIts_ = 0;
                return true;
            case 3:
                $this->nSCarnis_ = 0;
                return true;
            case 4:
                $this->nDCarnis_ = 0;
                return true;
            case 5:
                $this->nItQ_ = 0;
                return true;
            case 6:
                $this->nCarniQ_ = 0;
                return true;
        }

        return false;
    }



    public function getPackageOrder()
    {
        $order = array();
        $names = array(
            "singleI"  => "Single Italian Package",
            "doubleI"  => "Double Italian Package",
            "singleC"  => "Single Carniolan Package",
            "doubleC"  => "Double Carniolan Package",
            "ItalianQ" => "Separate Italian Queen Bee",
            "CarniQ"   => "Separate Carniolan Queen Bee"
        );

        if ($this->nSIts_ > 0)
            array_push($order,
                array("id" => 1, "name" => $names['singleI'],
                    "quantity" => $this->nSIts_,
                    "price" => BeePrices::getInstance()->getSQPackagePrice()));

        if ($this->nDIts_ > 0)
            array_push($order,
                array("id" => 2, "name" => $names['doubleI'],
                    "quantity" => $this->nDIts_,
                    "price" => BeePrices::getInstance()->getDQPackagePrice()));

        if ($this->nSCarnis_ > 0)
            array_push($order,
                array("id" => 3, "name" => $names['singleC'],
                    "quantity" => $this->nSCarnis_,
                    "price" => BeePrices::getInstance()->getSQPackagePrice()));

        if ($this->nDCarnis_ > 0)
            array_push($order,
                array("id" => 4, "name" => $names['doubleC'],
                    "quantity" => $this->nDCarnis_,
                    "price" => BeePrices::getInstance()->getDQPackagePrice()));

        if ($this->nItQ_ > 0)
            array_push($order,
                array("id" => 5, "name" => $names['ItalianQ'],
                    "quantity" => $this->nItQ_,
                    "price" => BeePrices::getInstance()->getQueenPrice()));

        if ($this->nCarniQ_ > 0)
            array_push($order,
                array("id" => 6, "name" => $names['CarniQ'],
                    "quantity" => $this->nCarniQ_,
                    "price" => BeePrices::getInstance()->getQueenPrice()));

        return $order;
    }



    public function getTransportationCharge()
    {
        $nPackages = $this->countPackages();

        switch ($this->pickupPoint_)
        {
            case 'Anchorage':
            case 'Wasilla':
            case 'Palmer':
            case 'Eagle River':
            case 'Big Lake':
                return 0;

            case 'Soldotna':
                return 5 * $nPackages;

            case 'Homer':
            case 'Healy':
            case 'Nenana':
            case 'Fairbanks':
                return 10 * $nPackages;

            case 'Other':
                return max($nPackages * 5, 10);

            default:
                return 0;
        }
    }



    public function getSingleCarniolanCount()
    {
        return $this->nSCarnis_;
    }


    public function getDoubleCarniolanCount()
    {
        return $this->nDCarnis_;
    }


    public function getSingleItalianCount()
    {
        return $this->nSIts_;
    }


    public function getDoubleItalianCount()
    {
        return $this->nDIts_;
    }


    public function getItalianQueenCount()
    {
        return $this->nItQ_;
    }


    public function getCarniolanQueenCount()
    {
        return $this->nCarniQ_;
    }


    public function countPackages()
    {
        return $this->nSIts_ + $this->nDIts_ +
            $this->nSCarnis_ + $this->nDCarnis_;
    }


    public function getPickupPoint()
    {
        return $this->pickupPoint_;
    }


    public function getCustomPickupPt()
    {
        return $this->customLoc_;
    }


    public function getActualDestination()
    {
        $dest = $this->getPickupPoint();
        if ($dest == "Other")
            $dest = $this->getCustomPickupPt();
        return $dest;
    }


    public function getNotes()
    {
        return $this->notes_;
    }


    public function getTotal()
    {
        return 0.00; //todo: ?
    }
}

?>
