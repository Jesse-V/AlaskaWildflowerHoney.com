<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/SuppliesOrder.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/BeeOrder.php');
    session_start();

    echo '
<!DOCTYPE html>
<html>
    <head>
        <title>Invisible Cart Manager!</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        ';

    global $db;

    if (empty($_POST) || !isset($_POST['format']))
    {
        echo "<script>window.history.back()</script>";
        return;
    }

    if ($_POST['format'] == "supplies")
    {
        $orderList = getSuppliesList();
        if (!validateSuppliesInputs($orderList))
            return;

        $itemIDs = "";
        foreach ($orderList as $item)
            $itemIDs .= $item['id'].',';
        $itemIDs = substr($itemIDs, 0, -1); //remove trailing comma

        if (empty($orderList))
            routeAccordingly("/order_bees.php");

        $suppliesSQL = $db->query("SELECT * FROM Supplies WHERE itemID IN ($itemIDs)");
        if (!$suppliesSQL)
            die("A fatal database issue was encountered in CartManager.php, Supplies query. Specifically, ".$db->error);

        $groups = queryGroups();
        $suppliesOrder = new SuppliesOrder($_POST['pickupLoc']);

        while ($record = $suppliesSQL->fetch_assoc())
        {
            $groupName = "";
            $groupDesc = "";
            if ($record['groupID'] > 0)
            {
                $groupName = trim($groups[$record['groupID']]['name']);
                $groupDesc = trim($groups[$record['groupID']]['description']);
            }

            $item = new SupplyItem($record['itemID'], trim($record['name']), trim($record['description']), $groupName, $groupDesc, $record['imageURL'], $record['price'], $orderList[$record['itemID']]['quantity']);
            $suppliesOrder->addItem($item);
        }

        $suppliesSQL->close();

        $_SESSION['supplies'] = $suppliesOrder;
        routeAccordingly("/order_bees.php");
    }
    else if ($_POST['format'] == "bees")
    {
        if (!validateBeesInputs())
            return;

        if (!isset($_POST['customDest']))
            $_POST['customDest'] = "";

        $_SESSION['beeOrder'] = new BeeOrder(
            ltrim($_POST['singleItalian'], "0"), //strip leading 0s (issue #46)
            ltrim($_POST['doubleItalian'], "0"),
            ltrim($_POST['singleCarni'],   "0"),
            ltrim($_POST['doubleCarni'],   "0"),
            ltrim($_POST['ItalianQueens'], "0"),
            ltrim($_POST['CarniQueens'],   "0"),
            $_POST['pickupLoc'],
            $_POST['customDest'],
            $_POST['notes']
        );

        routeAccordingly("/order_supplies.php");
    }
    else
    {
        echo "<script>window.history.back()</script>";
        return;
    }

    echo '
    </head>
    <body>
        Updating your cart and redirecting...
    </body>
</html>';


    function validateSuppliesInputs($suppliesOrderList)
    {
        if (!empty($suppliesOrderList) && !isset($_POST['pickupLoc']))
        {
            echo "<script>
                window.alert(\"You forgot to specify where you'd like to pick up the supplies. You will be redirected back to the form after you press OK.\");
                window.history.back();
            </script>";

            return false;
        }

        return true;
    }


    function validateBeesInputs()
    {
        if ($_POST['singleItalian'] + $_POST['doubleItalian'] + $_POST['singleCarni'] + $_POST['doubleCarni'] + $_POST['ItalianQueens'] + $_POST['CarniQueens'] == 0)
            $error = "you forgot to order packages or queens. Please specify a preference and resubmit.";
        else if (!isNumber($_POST['singleItalian']) || !isNumber($_POST['doubleItalian']) || !isNumber($_POST['singleCarni']) || !isNumber($_POST['doubleCarni']) || !isNumber($_POST['ItalianQueens']) || !isNumber($_POST['CarniQueens']))
            $error = "you must provide numeric values for your order.";
        else if (!isset($_POST['pickupLoc']))
            $error = "you forgot to specify a pickup location.";
        else if ($_POST['pickupLoc'] == "Other" && strlen($_POST['customDest']) <= 2)
            $error = "a custom destination was selected but it was not specified.";

        if (isset($error))
        {
            echo "<script>
                    window.alert(\"We ran into a problem with your request: $error You will be redirected back to the form after you press OK.\");
                    window.history.back();
                </script>";
            return false;
        }

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


    function getSuppliesList()
    {
        global $db;

        $incrementSQL = $db->query("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'Supplies';");
        if (!$incrementSQL)
            die("A fatal database issue was encountered in CartManager.php, AutoIncrement query. Specifically, ".$db->error);

        $supplies = array();
        $autoIncrementVal = $incrementSQL->fetch_assoc()['AUTO_INCREMENT'];
        for ($index = 1; $index < $autoIncrementVal; $index++)
        {
            if (isset($_POST[$index]) && $_POST[$index] > 0)
            {
                $item = array();
                $item['id'] = $index;
                $item['quantity'] = ltrim($_POST[$index], '0'); //strip leading 0s
                $supplies[$item['id']] = $item;
            }
        }

        return $supplies;
    }


    function routeAccordingly($alternative)
    {
        //if submit contains checkout, then direct to checkout_form
        $redirectURL = strpos($_POST['submit'], 'checkout') !== FALSE ? "1cart_checkout.php" : $alternative;

        echo "
            <script language=\"javascript\">
                window.location=\"{$redirectURL}\";
            </script>
            <meta http-equiv=\"refresh\" content=\"0;url={$redirectURL}\">
        ";
    }


    function isNumber($str)
    {
        return preg_match("/^\d+$/", $str);
    }
?>
