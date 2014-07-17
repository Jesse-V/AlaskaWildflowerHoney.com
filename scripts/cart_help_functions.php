<?php

require_once('databaseConnect.secret');
require_once('../checkout/order/SuppliesOrder.php');
require_once('../checkout/order/OrderBees.php');


function echoCart()
{
    $cart = getCart();
    $total = $cart['total'];
    echo $cart['html'];
    echo "<script>var total = $total;</script>";
    echo "<div class=\"total\">Total: $$total</div>";

    if (isset($_SESSION['supplies']))
        echo "<p>Pickup location: ".$_SESSION['supplies']->pickupLocation_."</p>";

    return $total;
}


function getCart()
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
                    <td>";

            if (strlen($item->imageURL_) > 0)
                $cartStr .= "<img src=\"$item->imageURL_\" alt=\"item\"/>";

            $cartStr .= "</td>
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
            </table>
            <div class=\"total\">Total: $$subtotal</div>';
    }

    if (isset($_SESSION['beeOrder']))
    {
        $prices = $_SESSION['beeOrder']->getPrices();

        $cartStr .= '
            <h2>Bees</h2>

            <table id="beesTable">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>';

        $subtotal = 0;
        $orderItems = $_SESSION['beeOrder']->getPackageOrder();
        foreach ($orderItems as $item)
        {
            $cartStr .= '
                <tr>
                    <td>'.$item['name'].'</td>
                    <td>'.$item['quantity'].'</td>
                    <td>$'.$item['price'].'</td>
                </tr>';
            $subtotal += $item['quantity'] * $item['price'];
        }

        $transpCharge = $_SESSION['beeOrder']->getTransportationCharge();
        if ($transpCharge > 0)
        {
            $dest = $_SESSION['beeOrder']->getPickupPoint();
            if ($dest == "Other")
                $dest = $_SESSION['beeOrder']->getCustomPickupPt()

            $cartStr .= "
                <tr>
                    <td>Transportation Charge to $dest</td>
                    <td></td>
                    <td>$$transpCharge</td>
                </tr>";

            $subtotal += $transpCharge;
        }

        $total += $subtotal;
        $cartStr .= '
            </table>
            <div class=\"total\">Total: $$subtotal</div>';
    }

    $total = number_format((float)$total, 2, '.', '');
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
