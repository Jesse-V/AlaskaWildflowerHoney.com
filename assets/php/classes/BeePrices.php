<?php

class BeePrices
{
    private $singleP_, $doubleP_, $queen_;


    function __construct($singlePackage, $doublePackage, $queen)
    {
        $this->singleP_ = $singlePackage;
        $this->doubleP_ = $doublePackage;
        $this->queen_   = $queen;
    }


    function getSQPackagePrice()
    {
        return $this->singleP_;
    }


    function getDQPackagePrice()
    {
        return $this->singleP_;
    }


    function getQueenPrice()
    {
        return $this->queen_;
    }
}

?>
