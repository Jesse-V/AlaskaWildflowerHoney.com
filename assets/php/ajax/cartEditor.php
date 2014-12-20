<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/SuppliesOrder.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/BeeOrder.php');

    session_start();

    try
    {
        if ($_GET['action'] == 'deleteItem')
        {
            if ($_GET['table'] == 'suppliesTable')
            {
                $suppliesOrder = $_SESSION['supplies'];
                $success = $suppliesOrder->removeItemByID($_GET['element']);
                if (count($suppliesOrder->getItems()) == 0)
                    unset($_SESSION['supplies']);

                echo $success ? "Success" : "Failure";
            }
            else if ($_GET['table'] == 'beesTable')
            {
                $beeOrder = $_SESSION['beeOrder'];
                $success = $beeOrder->removeOrderByID($_GET['element']);
                if ($beeOrder->countPackages() + $beeOrder->getItalianQueenCount() +
                    $beeOrder->getCarniolanQueenCount() == 0)
                    unset($_SESSION['beeOrder']);

                echo $success ? "Success" : "Failure";
            }
        }
        //TODO: edit quantity, change pickup, etc
    }
    catch (Exception $ex)
    {
        echo 'Failure. Caught exception: '.$ex->getMessage();
    }
?>
