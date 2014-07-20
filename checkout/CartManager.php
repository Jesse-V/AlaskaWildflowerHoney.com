<?php
    require_once('../assets/php/databaseConnect.secret');
    require_once('../assets/php/classes/SuppliesOrder.php');
    require_once('../assets/php/classes/BeeOrder.php');
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

            $item = new SupplyItem($record['name'], $record['description'], $groupName, $groupDesc, $record['imageURL'], $record['price'], $orderList[$record['itemID']]['quantity']);
            $suppliesOrder->addItem($item);
        }

        $suppliesSQL->close();

        $_SESSION['supplies'] = $suppliesOrder;
        routeAccordingly("../order_bees.php");
    }
    else if ($_POST['format'] == "bees")
    {
        if (!validateBeesInputs())
            return;

        $_SESSION['beeOrder'] = new BeeOrder(
            $_POST['singleItalian'],
            $_POST['doubleItalian'],
            $_POST['singleCarni'],
            $_POST['doubleCarni'],
            $_POST['ItalianQueens'],
            $_POST['CarniQueens'],
            $_POST['pickupLoc'],
            $_POST['customDest'],
            $_POST['notes']
        );

        routeAccordingly("../order_supplies.php");
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
        if (empty($suppliesOrderList))
        {
            echo "<script>
                window.alert(\"You forgot to order any supplies. You will be redirected back to the form after you press OK.\");
                window.history.back();
            </script>";

            return false;
        }

        if (!isset($_POST['pickupLoc']))
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
            die("Failed to connect to database. ".$db->error);

        $groups = array();
        while ($record = $groupSQL->fetch_assoc())
            $groups[$record['ID']] = $record;

        $groupSQL->close();
        return $groups;
    }


    function getSuppliesList()
    {
        $_MAXIMUM_NUM_OF_SUPPLIES = 250;
        $supplies = array();

        for ($index = 1; $index < $_MAXIMUM_NUM_OF_SUPPLIES; $index++)
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
