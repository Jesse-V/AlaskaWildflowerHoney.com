<?php

session_start();
require_once('databaseConnect.secret');
require_once('../checkout/order/Order.php');
require_once('../checkout/order/SuppliesOrder.php');


function echoCart()
{
    $supplyInfo = queryFetchSuppliesTable();
    $beeInfo = queryFetchBeesTable();
    $total = 0;

    if (isset($_SESSION['supplies']))
    {
        echo '
            <h2>Supplies</h2>

            <table id="cartTable">
                <tr>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>';

        print_r($_SESSION);
        $items = $_SESSION['supplies']->getItems();
        $subtotal = 0;

        foreach ($items as $item)
        {
            $price = $item->price_ * $item->quantity_;
            $subtotal += $price;

            echo "
                <tr>
                    <td><img src=\"$item->imageURL_\" alt=\"item\"</td>
                    <td>
                        $item->name_ $item->groupName_
                        <br>
                        $item->description_ $item->groupDescription_
                    </td>
                    <td>$item->quantity_</td>
                    <td>$$price</td>
                </tr>";
        }

        $total += $subtotal;
        echo '
            </table>
            <div class="subtotal">$$subtotal</div>';
    }

    if (isset($_SESSION['beeOrder']))
    { //todo: redo
        foreach ($_SESSION['beeOrder'] as $key => $value)
        {
            if (isset($beeInfo[$key]))
            {
                $name     = $beeInfo[$key]['name'];
                $quantity = $value;
                $price    = $beeInfo[$key]['price'] * $quantity;
                $total += $price;

                echo "
                    <tr>
                        <td>$name</td>
                        <td>$quantity</td>
                        <td>$$price</td>
                    </tr>";
            }
        }

        if (isset($_SESSION['beeOrder']['transCharge']) && $_SESSION['beeOrder']['transCharge'] > 0)
        {
            $charge = $_SESSION['beeOrder']['transCharge'];
            $dest = $_SESSION['beeOrder']['pickup'];
            if ($dest == "Other")
                $dest = $_SESSION['beeOrder']['destination'];

            echo "
                <tr>
                    <td>Transportation Charge to $dest</td>
                    <td></td>
                    <td>$$charge</td>
                </tr>";
            $total += $charge;
        }
    }

    return $total;
}



function queryFetchSuppliesTable()
{
    global $db;

    $suppliesSQL = $db->query("SELECT * FROM Supplies");
    if (!$suppliesSQL)
        die("Failed to connect to database. Could not fetch supplies information for the following reason: ".$db->error);

    $supplyInfo = array();
    while ($record = $suppliesSQL->fetch_assoc())
        $supplyInfo[$record['itemID']] = $record;

    $suppliesSQL->close();
    return $supplyInfo;
}



function queryFetchBeesTable()
{
    global $db;

    $beesSQL = $db->query("SELECT * FROM Bees");
    if (!$beesSQL)
        die("Failed to connect to database. Could not fetch bee prices for the following reason: ".$db->error);

    $beeInfo = array();
    while ($record = $beesSQL->fetch_assoc())
        $beeInfo[$record['ID']] = $record;

    $beesSQL->close();
    return $beeInfo;
}

?>
