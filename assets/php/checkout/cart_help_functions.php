<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/databaseConnect.secret');
require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/SuppliesOrder.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/classes/BeeOrder.php');


function echoCart()
{
    $cart = getCart();
    $total = $cart['total'];
    echo $cart['html'];
    echo "<script>var total = $total;</script>";
    echo "<div class=\"total\">Total: $$total</div>";

    return $total;
}


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
            <div class="subtotal">Subtotal: $'.$subtotal.'</div>';

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
                $dest = $_SESSION['beeOrder']->getCustomPickupPt();

            $cartStr .= "
                <tr>
                    <td>Transportation to $dest</td>
                    <td></td>
                    <td>$$transpCharge</td>
                </tr>";

            $subtotal += $transpCharge;
        }

        $total += $subtotal;
        $cartStr .= '
            </table>
            <div class="subtotal">Subtotal: $'.$subtotal.'</div>';
    }

    $total = number_format((float)$total, 2, '.', '');
    return array("total" => $total, "html" => $cartStr);
}


function getShippingContact($x)
{
    return  $x['x_ship_to_first_name'].' '.$x['x_ship_to_last_name'].'
            <br>
            Email: '.$x['x_email'].'
            <br>
            Primary Phone: '.$x['homePhone'].'
            <br>
            Secondary Phone: '.$x['cellPhone'];
}

?>
