<?php

require_once('databaseConnect.secret');
require_once('../checkout/order/SuppliesOrder.php');


function echoCart($suppliesObject)
{
    $cart = getCart($_SESSION['supplies']);
    $total = $cart['total'];
    echo $cart['html'];
    echo "<script>var total = $total;</script>";
    echo "<div class=\"total\">Total: $$total</div>";
    echo "<p>Pickup location: ".$_SESSION['supplies']->pickupLocation_."</p>";

    return $total;
}


function getCart($suppliesObject)
{
    $supplyInfo = queryFetchSuppliesTable();
    $beeInfo = queryFetchBeesTable();
    $total = 0;
    $cartStr = "";

    if (isset($_SESSION['supplies']))
    {
        $cartStr .= '
            <h2>Supplies</h2>

            <table id="cartTable">
                <tr>
                    <th>Image</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>';

        $items = $_SESSION['supplies']->getItems();
        $subtotal = 0;

        foreach ($items as $item)
        {
            $price = $item->price_ * $item->quantity_;
            $subtotal += $price;

            $cartStr .= "
                <tr>
                    <td><img src=\"$item->imageURL_\" alt=\"item\"/></td>
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
        $cartStr .= '
            </table>';
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

                $cartStr .= "
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

            $cartStr .= "
                <tr>
                    <td>Transportation Charge to $dest</td>
                    <td></td>
                    <td>$$charge</td>
                </tr>";
            $total += $charge;
        }
    }

    return array("total" => $total, "html" => $cartStr);
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


function queryFetchBeesTable() //todo: does this work?
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


function getShippingContact($x)
{
    return  $x['x_ship_to_first_name'].' '.$x['x_ship_to_last_name'].'
            <br>
            Email: '.$x['x_email'].'
            <br>
            Home Phone: '.$x['homePhone'].'
            <br>
            Cell: '.$x['cellPhone'].', texting? '.$x['textCapable'].'
            <br>
            Preferred Phone: '.$x['preferredPhone'];
}

?>
