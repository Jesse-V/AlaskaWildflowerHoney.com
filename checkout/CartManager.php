<?php
    session_start();
    require_once('../scripts/databaseConnect.secret');
    require_once('order/SuppliesOrder.php');
    require_once('order/BeeOrder.php');

    function queryGroups() //copy of order_supplies.php
    {
        global $db;

        $groupSQL = $db->query("SELECT * FROM SuppliesItemGroups");
        if (!$groupSQL)
            die("Failed to connect to database. ".$db->error);

        $groups = array();
        while ($record = $groupSQL->fetch_assoc())
            $groups[$record['ID']] = $record;

        $groupSQL->close();
        return $groups;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Invisible Cart Manager!</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php
    global $db;

    if (empty($_POST) || !isset($_POST['format']))
    {
        echo "<script>window.history.back()</script>";
        return;
    }

    if ($_POST['format'] == "supplies")
    {
        if (!validateSuppliesInputs())
            return;

        print_r($_POST);

        echo "<br><br>";

        $supplies = getSuppliesList();

        $itemIDs = "";
        foreach ($supplies as $item)
            $itemIDs .= $item['id'].',';
        $itemIDs = substr($itemIDs, 0, -1); //remove trailing comma

        $suppliesSQL = $db->query("SELECT * FROM Supplies WHERE itemID IN ($itemIDs)");
        if (!$suppliesSQL)
            die("Failed to fetch data. ".$db->error);

        $groups = queryGroups();
        $suppliesOrder = new SuppliesOrder($_POST['pickupLoc']);

        while ($record = $suppliesSQL->fetch_assoc())
        {
            $groupName = "";
            $groupDesc = "";
            if ($record['groupID'] > 0)
            {
                $groupName = $groups[$record['groupID']]['name'];
                $groupDesc = $groups[$record['groupID']]['description'];
            }

            $item = new SupplyItem($record['name'], $record['description'], $groupName, $groupDesc, $record['imageURL'], $record['price'], $supplies[$record['itemID']]['quantity']);
            $suppliesOrder->addItem($item);
        }

        print_r($suppliesOrder);
        echo "<br><br>";
        $suppliesSQL->close();

        $_SESSION['supplies'] = $suppliesOrder;
        routeAccordingly("../order_bees.php");
    }
    else if ($_POST['format'] == "bees")
    {
        if (!validateBeesInputs())
            return;

        $packageCount = $_POST['singleItalian'] + $_POST['doubleItalian'] + $_POST['singleCarni'] + $_POST['doubleCarni'];

        $beeOrder = array();
        if ($_POST['singleItalian'] > 0)
            $beeOrder['singleItalian'] = $_POST['singleItalian'];
        if ($_POST['doubleItalian'] > 0)
            $beeOrder['doubleItalian'] = $_POST['doubleItalian'];
        if ($_POST['singleCarni'] > 0)
            $beeOrder['singleCarni'] = $_POST['singleCarni'];
        if ($_POST['doubleCarni'] > 0)
            $beeOrder['doubleCarni'] = $_POST['doubleCarni'];

        if ($_POST['ItalianQueens'] > 0)
            $beeOrder['ItalianQueens'] = $_POST['ItalianQueens'];
        if ($_POST['CarniQueens'] > 0)
            $beeOrder['CarniQueens'] = $_POST['CarniQueens'];

        $beeOrder['pickup'] = $_POST['pickupLoc'];
        $beeOrder['destination'] = $_POST['customDest'];
        $beeOrder['notes'] = $_POST['notes'];
        $beeOrder['transCharge'] = getTransportationCharge($beeOrder['pickup'], $packageCount);

        $_SESSION['beeOrder'] = $beeOrder;

        routeAccordingly("../order_supplies.php");
    }
    else
    {
        echo "<script>window.history.back()</script>";
        return;
    }
?>

    </head>
    <body>
        Updating your cart and redirecting...
    </body>
</html>

<?php

    function validateSuppliesInputs()
    {
        if (!isset($_POST['pickupLoc']))
        {
            echo "<script>
                window.alert(\"You forgot to specify where you'd like to pick up the supplies. You will be redirected back to the form after you press 'OK'.\");
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
                    window.alert(\"We ran into a problem with your request: $error You will be redirected back to the form after you press 'OK'.\");
                    window.history.back();
                </script>";
            return false;
        }

        return true;
    }



    function getSuppliesList()
    {
        $supplies = array();

        for ($index = 1; isset($_POST[$index]); $index++)
        {
            if ($_POST[$index] > 0)
            {
                $item = array();
                $item['id'] = $index;
                $item['quantity'] = $_POST[$index];
                $supplies[$item['id']] = $item;
            }
        }

        return $supplies;
    }



    function getTransportationCharge($destination, $packageCount)
    {
        switch ($destination)
        {
            case 'Anchorage':
            case 'Wasilla':
            case 'Palmer':
            case 'Eagle River':
            case 'Big Lake':
                return 0;

            case 'Soldotna':
                return 5 * $packageCount;

            case 'Homer':
            case 'Healy':
            case 'Nenana':
            case 'Fairbanks':
                return 10 * $packageCount;

            case 'Other':
                return max($packageCount * 5, 10);

            default:
                return 0;
        }
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
