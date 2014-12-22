<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/SuppliesOrder.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/BeeOrder.php');

    session_start();

    try
    {
        if (empty($_GET['action']))
            return;

        if ($_GET['action'] == 'deleteItem')
        {
            if ($_GET['table'] == 'suppliesTable')
            {
                //remove supply selection by itemID
                $suppliesOrder = $_SESSION['supplies'];
                $success = $suppliesOrder->removeItemByID($_GET['element']);
                if (count($suppliesOrder->getItems()) == 0)
                    unset($_SESSION['supplies']);

                echo $success ? "Success" : "Failure";
            }
            else if ($_GET['table'] == 'beesTable')
            {
                //remove bee order by bee/queen name
                $beeOrder = $_SESSION['beeOrder'];
                $success = $beeOrder->removeOrderByID($_GET['element']);
                if ($beeOrder->countPackages() + $beeOrder->getItalianQueenCount() +
                    $beeOrder->getCarniolanQueenCount() == 0)
                    unset($_SESSION['beeOrder']);

                echo $success ? "Success" : "Failure";
            }
        }
        else if ($_GET['action'] == 'updateOrder')
        {
            if ($_GET['page'] == 'supplies')
            {
                //add items to order in batch, replacing any existing supplies order
                $success = applySuppliesOrder($_GET['selection'], $_GET['pickupLoc']);
                echo $success ? "Success" : "Failure";

                //print_r($_GET);
            }
            else if ($_GET['page'] == 'bees')
            {
            }
        }

        //TODO: edit quantity, change pickup, etc
    }
    catch (Exception $ex)
    {
        echo 'Failure. Caught exception: '.$ex->getMessage();
    }



    function applySuppliesOrder($selection, $pickupLoc)
    {
        global $db;

        //make list of selection itemIDs
        $itemIDs = "";
        foreach (array_keys($selection) as $itemID)
            $itemIDs .= $itemID.',';
        $itemIDs = substr($itemIDs, 0, -1); //remove trailing comma

        //fetch on database info from those itemIDs
        $suppliesSQL = $db->query("SELECT * FROM Supplies WHERE itemID IN ($itemIDs)");
        if (!$suppliesSQL)
            return false;
            //die("A fatal database issue was encountered in cartManager.php, applySuppliesOrder. Specifically, ".$db->error);

        $groups = queryGroups();
        $suppliesOrder = new SuppliesOrder($pickupLoc);

        //assemble order with full database information
        while ($record = $suppliesSQL->fetch_assoc())
        {
            //get group name and description if part of a group
            $groupName = "";
            $groupDesc = "";
            if ($record['groupID'] > 0)
            {
                $groupName = $groups[$record['groupID']]['name'];
                $groupDesc = $groups[$record['groupID']]['description'];
            }

            $item = new SupplyItem($record['itemID'], trim($record['name']),
                trim($record['description']), trim($groupName), trim($groupDesc),
                $record['imageURL'], $record['price'],
                trim($selection[$record['itemID']], "0")); //fixes issue #46
            $suppliesOrder->addItem($item);
        }

        $suppliesSQL->close();
        $_SESSION['supplies'] = $suppliesOrder;

        return true;
    }



    function queryGroups() //duplicate of method in order_supplies.php
    {
        global $db;

        $groupSQL = $db->query("SELECT * FROM SuppliesItemGroups");
        if (!$groupSQL)
            die("A fatal database issue was encountered in CartManager.php - Group query. Specifically, ".$db->error);

        $groups = array();
        while ($record = $groupSQL->fetch_assoc())
            $groups[$record['ID']] = $record;

        $groupSQL->close();
        return $groups;
    }
?>
