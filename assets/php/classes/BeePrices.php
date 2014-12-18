<?php

require_once(__DIR__.'/../databaseConnect.secret');

class BeePrices
{
    private static $instance;
    private $singleP_, $doubleP_, $queen_;


    private function __construct()
    {
        global $db;

        $beesSQL = $db->query("SELECT * FROM Bees");
        if (!$beesSQL)
            die("A fatal database issue was encountered in index.php, Bees query. Specifically, ".$db->error);

        $prices = array();
        while ($record = $beesSQL->fetch_assoc())
            $prices[$record['name']] = $record['price'];

        $this->singleP_ = $prices['singlePrice'];
        $this->doubleP_ = $prices['doublePrice'];
        $this->queen_   = $prices['queenPrice'];
    }


    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }


    function getSQPackagePrice()
    {
        return $this->singleP_;
    }


    function getDQPackagePrice()
    {
        return $this->doubleP_;
    }


    function getQueenPrice()
    {
        return $this->queen_;
    }
}

?>
