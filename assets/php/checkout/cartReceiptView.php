<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/SuppliesOrder.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/BeeOrder.php');


//echos the cart's html, returns the total
function echoCart()
{
    $cart = getCart();
    $total = $cart['total'];
    echo $cart['html'];
    echo "<script>var total = $total;</script>";
    echo "<div class=\"total\">Total: $$total</div>";

    return $total;
}


//returns an array containing the carts html and total
function getCart()
{
    $total = 0;
    $cartStr = "";

    if (isset($_SESSION['supplies']))
    {
        $cartStr .= '
            <h2>Supplies</h2>

            <table class="cartTable">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>';

        $items = $_SESSION['supplies']->getItems();
        $subtotal = 0;

        //print each supply item as a table row
        foreach ($items as $item)
        {
            $price = $item->price_ * $item->quantity_;
            $subtotal += $price;

            $cartStr .= "
                <tr>
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
            <div class="subtotal">Subtotal: $'
            .number_format((float)$subtotal, 2, '.', '').
            '</div>';

        if (isset($_SESSION['supplies']))
           $cartStr .= "<p>Pickup location: ".$_SESSION['supplies']->pickupLocation_."</p>";
    }

    if (isset($_SESSION['beeOrder']))
    {
        $cartStr .= '
            <h2>Bees</h2>

            <table class="cartTable">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>';

        //print each supply bee order selection as a table row
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

        //show transportation charges
        $transpCharge = $_SESSION['beeOrder']->getTransportationCharge();
        $dest = $_SESSION['beeOrder']->getPickupPoint();
        if ($dest == "Other")
            $dest = $_SESSION['beeOrder']->getCustomPickupPt();

        $eta = 'April '.$_SESSION['beeOrder']->getPickupDate().'th';

        $cartStr .= "
            <tr>
                <td>Transportation to $dest on $eta
                </td>
                <td></td>
                <td>$$transpCharge</td>
            </tr>";

        $subtotal += $transpCharge;

        $total += $subtotal;
        $cartStr .= '
            </table>
            <div class="subtotal">Subtotal: $'
            .number_format((float)$subtotal, 2, '.', '').
            '</div>';
    }

    $total = number_format((float)$total, 2, '.', '');
    return array("total" => $total, "html" => $cartStr);
}



//print customer's name and basic contact information
function getShippingContact($x)
{
    return  $x['x_ship_to_first_name'].' '.$x['x_ship_to_last_name'].'
            <br>
            Email: '.$x['x_email'].'
            <br>
            Primary Phone: '.$x['primaryPhone'].'
            <br>
            Secondary Phone: '.$x['backupPhone'];
}

?>
