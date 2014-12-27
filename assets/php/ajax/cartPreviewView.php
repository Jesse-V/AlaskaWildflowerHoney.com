<?php
    //used to render the HTML for the cart preview on the right-hand side

    require_once($_SERVER['DOCUMENT_ROOT'].'/assets/php/checkout/cart_help_functions.php');
    if (!isset($_SESSION))
        session_start();


    if (!empty($_GET))
    {
        if ($_GET['action'] == 'getHTML')
            echo getPreviewHTML();
        if ($_GET['action'] == 'getTotal')
            echo getPreviewTotal();
        if ($_GET['action'] == 'getAll')
        {
            header('Content-Type: application/json');
            echo json_encode(array('html' => getPreviewHTML(), 'total' => getPreviewTotal()));
        }
    }


    function getPreviewHTML()
    {
        $str = "<table>";

        if (isset($_SESSION['supplies']))
        {
            $items = $_SESSION['supplies']->getItems();
            foreach ($items as $item)
            {
                $name = "$item->name_ $item->groupName_";
                if (strlen($name) > 35)
                    $name = substr($name, 0, 32)."...";

                $str .= "<tr>
                        <td>$name</td>
                        <td>$item->quantity_</td>
                    </tr>";
            }
        }

        if (isset($_SESSION['beeOrder']))
        {
            $orderItems = $_SESSION['beeOrder']->getPackageOrder();
            foreach ($orderItems as $item)
            {
                $str .= "<tr>
                        <td>".$item['name']."</td>
                        <td>".$item['quantity']."</td>
                    </tr>";
            }
        }

        return $str."</table>";
    }



    function getPreviewTotal()
    {
        return '<div class="total">Total: $'.getCart()['total'].'</div>';
    }
?>
