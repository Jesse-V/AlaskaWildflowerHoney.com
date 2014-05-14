<?php

require_once('Order.php');

class BeeOrder extends Order
{
    private $nSingleCarniolans_ = 0;
    private $nDoubleCarniolans_ = 0;

    private $nSingleItalians_ = 0;
    private $nDoubleItalians_ = 0;

    private $nItalianQueen_   = 0;
    private $nCarniolanQueen_ = 0;

    private $pickupPoint_;
    private $firstName_, $lastName;
    private $homeNumber_, $cellNumber_;
    private $preferredPhone_, $textCapable_;
    private $emailAddress_;
    private $notes_;

    protected function _getTotal()
    {
        return 0.00;
    }
}

?>
