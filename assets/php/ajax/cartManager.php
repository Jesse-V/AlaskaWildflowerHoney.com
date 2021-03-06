<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/SuppliesOrder.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/BeeOrder.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/inputSanitize.php');

    if (!isset($_SESSION))
        session_start();

    $input = sanitizeArray($_POST);


    try
    {
        if (empty($input['action']))
            return;

        //if an item should be deleted
        if ($input['action'] == 'deleteItem')
        {
            //if the item is a supplies item
            if ($input['table'] == 'suppliesTable')
            {
                //remove supply selection by itemID
                $success = $_SESSION['supplies']->removeItemByID($input['element']);
                if (count($_SESSION['supplies']->getItems()) == 0)
                    unset($_SESSION['supplies']);

                echo $success ? "Success" : "Failure";
            }
            else if ($input['table'] == 'beesTable') //if it's a bee item
            {
                //remove bee order by bee/queen name
                $beeOrder = $_SESSION['beeOrder'];
                $success = $beeOrder->removeOrderByID($input['element']);
                if ($beeOrder->countPackages() + $beeOrder->getItalianQueenCount() +
                    $beeOrder->getCarniolanQueenCount() == 0)
                    unset($_SESSION['beeOrder']);

                echo $success ? "Success" : "Failure";
            }
        }
        else if ($input['action'] == 'updateOrder') //we are updating the order
        {
            //add items to order in batch, replacing any existing supplies order
            if ($input['page'] == 'supplies')
            {
                $success = updateSuppliesOrder($input['selection'], $input['pickupLoc']);
                echo $success ? "Success" : "Failure";
            }
            else if ($input['page'] == 'bees')
            {
                //sum the number of selected packages and queens
                $count = 0;
                foreach ($input['selection'] as $item)
                    $count += $item;

                header('Content-Type: application/json');
                if ($count == 0) //if no packages or queens are selected
                {
                    unset($_SESSION['beeOrder']); //clear bee order
                    echo json_encode(array("status" => "Success",
                        "transCharge" => 0, "subtotal" => 0));
                }
                else
                {
                    $success = updateBeeOrder($input['selection'],
                        $input['pickup']) ? "Success" : "Failure";
                    $transCharge = $_SESSION['beeOrder']->getTransportationCharge();
                    $subtotal = $_SESSION['beeOrder']->getSubtotal();

                    echo json_encode(array("status" => $success,
                        "transCharge" => $transCharge, "subtotal" => $subtotal));
                }
            }
        }
        else if ($input['action'] == 'changeQuantity') //editing quantity via editor
        {
            //if the item is a supplies item
            if ($input['table'] == 'suppliesTable')
            {
                $success = $_SESSION['supplies']->changeQuantityOnItem(
                    $input['element'], $input['newQuantity']);
                if (count($_SESSION['supplies']->getItems()) == 0)
                    unset($_SESSION['supplies']);

                echo $success ? "Success" : "Failure";
            }
            else if ($input['table'] == 'beesTable') //if it's a bee item
            {
                $beeOrder = $_SESSION['beeOrder'];
                $success = $beeOrder->changeQuantity(
                    $input['element'], $input['newQuantity']);
                if ($beeOrder->countPackages() + $beeOrder->getItalianQueenCount() +
                    $beeOrder->getCarniolanQueenCount() == 0)
                    unset($_SESSION['beeOrder']);

                echo $success ? "Success" : "Failure";
            }
        }
    }
    catch (Exception $ex)
    {
        echo 'Failure. Caught exception: '.$ex->getMessage();
    }



    //updates the session's supplies order with the given selection and pickup info
    function updateSuppliesOrder($selection, $pickupLoc)
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
                ltrim($selection[$record['itemID']], "0")); //fixes issue #46
            $suppliesOrder->addItem($item);
        }

        $suppliesSQL->close();
        $_SESSION['supplies'] = $suppliesOrder;

        return true;
    }



    //updates the session's bee order with the given selection and pickup info
    function updateBeeOrder($selection, $pickup)
    {
        if (isset($_SESSION['beeOrder']))
        {
            //if choice is already other and custom location stored, and given other with no custom location, then migrate choice
            if ($_SESSION['beeOrder']->getPickupPoint() == "Other" &&
                strlen($_SESSION['beeOrder']->getCustomPickupPt()) > 0 &&
                $pickup['pickupLoc'] == "Other" &&
                strlen($pickup['customDest']) == 0)
                $pickup['customDest'] = $_SESSION['beeOrder']->getCustomPickupPt();
        }

        //if they are undefined, make them blank to avoid server warnings
        if (!isset($pickup['customDest']))
            $pickup['customDest'] = "";
        if (!isset($pickup['pickupDate']))
            $pickup['pickupDate'] = "";

        $_SESSION['beeOrder'] = new BeeOrder(
            ltrim($selection['singleItalian'], "0"), //strip leading 0s (issue #46)
            ltrim($selection['doubleItalian'], "0"),
            ltrim($selection['singleCarni'],   "0"),
            ltrim($selection['doubleCarni'],   "0"),
            ltrim($selection['ItalianQueens'], "0"),
            ltrim($selection['CarniQueens'],   "0"),
            trim($pickup['pickupLoc']),
            trim($pickup['customDest']),
            trim($pickup['pickupDate']),
            trim($pickup['notes'])
        );

        return true;
    }



    function queryGroups() //TODO: duplicate of method in order_supplies.php
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
